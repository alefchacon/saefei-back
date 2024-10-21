<?php

namespace App\Models;

use App\Mail\MailProvider;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
            ::where("idTipoAviso", "=", TipoAvisoEventEnum::evento_nuevo)
            ->orWhere("idTipoAviso", "=", TipoAvisoEventEnum::evento_evaluado)
            ->where("idEvento", "<>", null)
            ->where("visto", 0);
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

        $administrator = Administrador::where("idUsuario", $idUsuario)->first();
        if (!$administrator){
            throw new AccessDeniedException("El usuario con id {$idUsuario} no es administrador");
        }

        return $query
            ->select("avisos.*")
            ->where("idReservacion", "<>", null)
            ->where("visto", 0)
            ->where("idTipoAviso", TipoAvisoReservationEnum::reservacion_nueva)
            ->join("reservaciones", "avisos.idReservacion", "=", "reservaciones.id")
            ->join("espacios", "reservaciones.idEspacio", "=", "espacios.id")
            ->where("espacios.idAdministrador", "=", $administrator->id);
    }


    public static function notifyResponse(
        int $idAviso, 
        Evento|Reservacion $notification, 
        int $originalIdEstado
    ){

        $replyingToEventOrganizer = 
            $originalIdEstado !== $notification->idEstado;

        if (!$replyingToEventOrganizer){
            return;
        }

        Aviso::where("id", "=", $idAviso)
            ->update(["visto" => 1]);
        
        $isEvent =  $notification instanceof Evento;
        $idTipoAviso = 0;
        
        $isEvent
        ? $idTipoAviso = TipoAvisoEventEnum::tryFrom($notification->idEstado)
        : $idTipoAviso = TipoAvisoReservationEnum::mapFrom($notification->idEstado);
        
        Aviso::create([
            "visto" => 0,
            "idUsuario" => $notification->usuario->id,
            "idEstado" => $notification->idEstado,
            "idEvento" => $isEvent ? $notification->id : null,
            "idReservacion" => !$isEvent ? $notification->id : null,
            "idTipoAviso" => $idTipoAviso
        ]);

        //$notification->load('usuario');
        //MailFactory::sendEventReplyMail($notification);
    }


    


}
