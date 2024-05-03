<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use HasFactory;
    protected $fillable = [
        "nombre"
    ];

    public function eventos(){
        return $this->hasMany(Evento::class, 'idEstado', 'id');
    }
    public function solicitudesEspacios(){
        return $this->hasMany(SolicitudEspacio::class, 'idEstado', 'id');
    }
}
