<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;
    

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado"
    ];

    public function solicitudesEspacios(){
        return $this->hasMany(SolicitudEspacio::class, 'idSolicitud', 'id');
    }
}
