<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    use HasFactory;
    protected $table = 'respuestas';
    public $timestamps = false;

    protected $fillable = [
        "observaciones",
        "idEstado",
        "vistoOrganizador",
        "vistoStaff"
    ];

    public function evento() {
        return $this->hasOne(Evento::class, 'idRespuesta', 'id');
    }
    public function reservacion() {
        return $this->hasOne(Reservacion::class, 'idRespuesta', 'id');
    }
}
