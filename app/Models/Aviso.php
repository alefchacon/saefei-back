<?php

namespace App\Models;

use App\Mail\MailService;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
