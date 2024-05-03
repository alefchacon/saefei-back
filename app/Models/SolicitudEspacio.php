<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudEspacio extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'idUsuario',
        'idEspacio',
        'idEstado',
        'idEvento',
        'respuesta'
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

}
