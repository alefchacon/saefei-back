<?php

namespace App\Models;

use App\Models\Enums\EstadoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Mail\MailProvider;
use App\Mail\MailService;
use App\Models\Enums\TipoAvisoReservationEnum;
use App\Models\Espacio;
use App\Models\User;
use App\Models\Aviso;

class Reservacion extends Model
{
    use HasFactory;
    protected $table = 'reservaciones';
    public $timestamps = false;

    protected $fillable = [
        'fecha',
        'motivo',
        'inicio',
        'fin',
        'idUsuario',
        'idEspacio',
        'idEstado',
        'idEvento',
        'respuesta',
        'avisarAdministrador',
        'avisarUsuario',
    ];
    

    
    public function usuario(){
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

    public function estado(){
        return $this->belongsTo(Estado::class, 'idEstado', 'id');
    }

    public function espacio(){
        return $this->belongsTo(Espacio::class, 'idEspacio', 'id');
    }

    public function evento(){
        return $this->belongsTo(Evento::class, 'idEvento', 'id');
    }

    public function respuesta(){
        return $this->belongsTo(Respuesta::class, 'idRespuesta', 'id');
    }

    public function actividades(){
        return $this->hasMany(Actividad::class, 'idReservacion', 'id');
    }

    public function accept(){
        $this->idEstado = EstadoEnum::aceptado->value;
        $this->save();

        Aviso::notifyResponse(
            $this, TipoAvisoReservationEnum::reservacion_aceptada
        );

        self::handleReservationMail($this);
    }

    public function reject(string $reply){
        $this->idEstado = EstadoEnum::rechazado->value;
        $this->respuesta = $reply;
        $this->save();

        Aviso::notifyResponse(
            $this, TipoAvisoReservationEnum::reservacion_rechazada
        );

        self::handleReservationMail($this);
    }

    private static function handleReservationMail(Reservacion $reservation)
    {
        $type = TipoAvisoReservationEnum::mapFrom($reservation->idEstado);
        
        $mail = MailProvider::getReservationMail(
            $reservation, 
            $type
        );

        $reservation->load('usuario');
        MailService::sendEmail(
            to: $reservation->usuario,
            mail: $mail 
        );
    }
}
