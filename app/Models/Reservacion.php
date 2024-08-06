<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservacion extends Model
{
    use HasFactory;
    protected $table = 'reservaciones';
    public $timestamps = false;

    protected $fillable = [
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

}
