<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluacion extends Model
{
    protected $table = 'evaluaciones';
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        "calificacionAtencion",
        "razonCalificacionAtencion",
        "calificacionComunicacion",
        "mejorasApoyo",
        "calificacionEspacio",
        "problemasEspacio",
        "calificacionCentroComputo",
        "razonCalificacionCentroComputo",
        "calificacionRecursos",
        "razonCalificacionRecursos",
        "problemaRecursos",
        "mejorasRecursos",
        "adicional",
        "idEvento"
    ];

    public function evento() {
        return $this->belongsTo(Evento::class);
    }
}

