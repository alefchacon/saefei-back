<?php

namespace App\Models;

use App\Mail\MailProvider;
use App\Mail\MailService;
use App\Models\Enums\EstadoEnum;
use App\Models\Enums\RolEnum;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Mail\Mail;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class Aviso extends Model
{
    use HasFactory;
    protected $table = 'avisos';

    protected $fillable = [
        "visto",
        "idEvento",
        "idReservacion",
        "idUsuario",
        "idEstado",
        "idTipoAviso"
    ];

    public function reservacion(){
        return $this->belongsTo(Reservacion::class, 'idReservacion', 'id');
    }
    public function evento(){
        return $this->belongsTo(Evento::class, 'idEvento', 'id');
    }
    public function tipoAviso(){
        return $this->belongsTo(TipoAviso::class, 'idTipoAviso', 'id');
    }

    
    public static function getCoordinatorNotices(){
        return Aviso
            ::where(function($query) {
                $query->where("idTipoAviso", TipoAvisoEventEnum::evento_nuevo)
                    ->orWhere("idTipoAviso", TipoAvisoEventEnum::evento_evaluado);
            })
            ->where("idEvento", "<>", null)
            ->where("visto", "=", 0);
    } 

    public function scopeGetEventNoticesFor($query, $idUsuario){
        return $query
            ->select("avisos.*")
            ->join('eventos', 'avisos.idEvento', "=", "eventos.id")
            ->where("visto", 0)
            ->where("idTipoAviso", TipoAvisoEventEnum::evento_aceptado)
            ->where("eventos.idUsuario", $idUsuario);
    }
        
    public function scopeGetReservationNoticesFor($query, $idUsuario){
        return $query
            ->select("avisos.*")
            ->where("visto", 0)
            ->where("idTipoAviso", TipoAvisoReservationEnum::reservacion_aceptada)
            ->orWhere("idTipoAviso", TipoAvisoReservationEnum::reservacion_rechazada)
            ->join('reservaciones', 'avisos.idReservacion', "=", "reservaciones.id")
            ->where("reservaciones.idUsuario", $idUsuario);
    }

    public function scopeGetAdministratorNoticesFor($query, $idUsuario){

        $administratorIds =  User
            ::find($idUsuario)
            ->administradores()
            ->pluck("id")
            ->toArray();

        return $query
            ->select("avisos.*")
            ->where("idReservacion", "<>", null)
            ->where("visto", 0)
            ->where("idTipoAviso", TipoAvisoReservationEnum::reservacion_nueva)
            ->join("reservaciones", "avisos.idReservacion", "=", "reservaciones.id")
            ->join("espacios", "reservaciones.idEspacio", "=", "espacios.id")
            ->whereIn("espacios.idAdministrador", $administratorIds);
        }

    public static function notifyNewReservation(Reservacion $reservation){
        Aviso::create([
            "visto" => 0,
            "idReservacion" => $reservation->id,
            "idTipoAviso" => TipoAvisoReservationEnum::reservacion_nueva
        ]);
        $mail = MailProvider::getReservationMail(
            $reservation, 
            TipoAvisoReservationEnum::reservacion_nueva
        );
        $adminsOfSelectedSpace = Administrador
            ::with("users")
            ->find($reservation->espacio->idAdministrador);

        foreach($adminsOfSelectedSpace->users as $admin)
        {
            MailService::sendEmail(
                to: $admin, 
                mail: $mail
            );
        }
    }
    public static function notifyEventUpdate(Evento $evento, User $editor){
        $editorIsCoordinator = $editor->isCoordinator();
        $type = $editorIsCoordinator
            ? TipoAvisoEventEnum::evento_editado_coordinador 
            : TipoAvisoEventEnum::evento_editado_organizador ;
        Aviso::create([
            "visto" => 0,
            "idEvento" => $evento->id,
            "idTipoAviso" => $type->value
        ]);
        
        $mail = MailProvider::getEventUpdateMail(
            $evento, 
            $type
        );
        
        if ($editorIsCoordinator){
            $evento->load("usuario");
            MailService::sendEmail(
                to: $evento->usuario, 
                mail: $mail
            );
        }else {
            self::notifyCoordinators($mail);
        }
    }
    public static function notifyNewEvent(Evento $event){
        Aviso::create([
            "visto" => 0,
            "idEvento" => $event->id,
            "idTipoAviso" => TipoAvisoEventEnum::evento_nuevo
        ]);
        $mail = MailProvider::getEventMail(
            event: $event,
            type: TipoAvisoEventEnum::evento_nuevo
        );
        self::notifyCoordinators($mail);
    }

    static function notifyCoordinators(Mail $mail){
        $coordinators = User
        ::whereHas('roles', function($query) {
            $query->where('roles.id', RolEnum::coordinador->value);
        })->get();

    foreach($coordinators as $coordinator){
        MailService::sendEmail(
            to: $coordinator, 
            mail: $mail
        );
    }
    }


    public static function notifyResponse(
        Evento|Reservacion $notification, 
        TipoAvisoEventEnum|TipoAvisoReservationEnum $replyType
    ){
        $isEvent = $notification instanceof Evento;

        $notificationTable = $isEvent ? "Evento" : "Reservacion";
        $tipoAviso = $isEvent ? TipoAvisoEventEnum::evento_nuevo : TipoAvisoReservationEnum::reservacion_nueva;

        Aviso
            ::where("id{$notificationTable}", "=", $notification->id)
            ->where("idTipoAviso", "=", $tipoAviso->value)
            ->update(["visto" => 1]);
        
        
        Aviso::create([
            "visto" => 0,
            "idEvento" => $isEvent ? $notification->id : null,
            "idReservacion" => !$isEvent ? $notification->id : null,
            "idTipoAviso" => $replyType->value
        ]);
    }


    


}
