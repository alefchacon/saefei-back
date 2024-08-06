<?php

namespace App\Models;

use App\Mail\MailFactory;
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
        "idEstado"
    ];

    public function reservacion(){
        return $this->belongsTo(Reservacion::class, 'idReservacion', 'id');
    }
    public function evento(){
        return $this->belongsTo(Evento::class, 'idEvento', 'id');
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

        $notification->load('usuario');

        Aviso::where("id", "=", $idAviso)
        ->update(["visto" => 1]);
        
        Aviso::create([
            "visto" => 0,
            "idUsuario" => $notification->usuario->id,
            "idEstado" => $notification->idEstado,
            "idEvento" => $notification instanceof Evento ? $notification->id : null,
            "idReservacion" => $notification instanceof Reservacion ? $notification->id : null,
        ]);
        //MailFactory::sendEventReplyMail($notification);
    }
}
