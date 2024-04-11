<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudEspacio extends Model
{
    use HasFactory;

    protected $fillable = [
        'idEspacio',
        'idEstado',
        'idHorario',
        'respuesta'
    ];
    

    
    public function estado(){
        return $this->hasOne(Estado::class, 'idEstado');
    }

    public function espacio(){
        return $this->hasOne(Espacio::class, 'idEspacio');
    }

    public function horario(){
        return $this->hasOne(Horario::class, 'idHorario');
    }
}
