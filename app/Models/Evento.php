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
        "respuesta"
    ];

    public function evaluacion() {
        return $this->hasOne(Evaluacion::class, 'idEvento');
    }
}
