<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $table = 'eventos';
    public $timestamps = false;
    protected $fillable = [
        "nombre",
        "descripcion",
        "numParticipantes",
        "cronograma",
        "requisitosCentroComputo",
        "numParticipantesExternos",
        "requiereEstacionamiento",
        "requiereFinDeSemana",
        "requiereMaestroDeObra",
        "requiereNotificarPrensaUV",
        "adicional",
        "respuesta",
        "idUsuario",
        "idModalidad",
        "idEstado",
        "idTipo",
    ];

    public function estado() {
        return $this->hasOne( Estado::class, 'idEstado', 'id');
    }

    public function modalidad(){
        return $this->hasOne(Modalidad::class, 'idModalidad', 'id');
    }

    public function tipo(){
        return $this->hasOne(Tipo::class, 'idTipo', 'id');
    }

}
