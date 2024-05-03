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

        "inicio",
        "fin",

        "adicional",
        "respuesta",
        "idUsuario",
        "idModalidad",
        "idEstado",
        "idTipo",
    ];

    public function usuario() {
        return $this->belongsTo( User::class, 'idUsuario', 'id');
    }

    public function evaluacion() {
        return $this->hasOne( Evaluacion::class, 'idEvento', 'id');
    }
    public function estado() {
        return $this->belongsTo( Estado::class, 'idEstado', 'id');
    }

    public function modalidad(){
        return $this->belongsTo(Modalidad::class, 'idModalidad', 'id');
    }

    public function tipo(){
        return $this->belongsTo(Tipo::class, 'idTipo', 'id');
    }

    public function solicitudesEspacios(){
        return $this->hasMany(SolicitudEspacio::class, 'idEvento', 'id');
    }

    public static function encontrarPor($anio, $mes){
        return self::whereHas('solicitudesEspacios', function ($query) use ($anio, $mes) {
            $query->whereYear('inicio', '=', $anio)
                  ->whereMonth('inicio', '=', $mes);
        })
        ->with([ 
            'solicitudesEspacios.usuario', 
            'solicitudesEspacios.espacio', 
            'solicitudesEspacios.estado'
        ])
        ->get();
    }
}
