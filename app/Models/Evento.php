<?php

namespace App\Models;

use App\Models\Enums\EstadoEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $table = 'eventos';
    //public $timestamps = false;
    protected $fillable = [
        "nombreOrganizador",
        "puesto",
        "email",
        "nombre",
        "descripcion",
        "pagina",
        "ambito",
        "audiencias",
        "eje",
        "tematicas",

        "inicio",
        "fin",
        "numParticipantes",

        "plataformas",

        "requisitosCentroComputo",
        "requiereTransmisionEnVivo",
        "presidium",
        "decoracion",
        "numParticipantesExternos",
        "requiereEstacionamiento",
        "requiereFinDeSemana",
        
        "medios",

        "requiereConstancias",
        "ponientes",
        
        "adicional",

        "observaciones",

        "idUsuario",
        "idModalidad",
        "idPrograma",
        "idPlataforma",
        "idEstado",
        "idTipo",
    ];

    public function usuario() {
        return $this->belongsTo( User::class, 'idUsuario', 'id');
    }

    public function evidencias(){
        return $this->hasManyThrough(Evidencia::class, Evaluacion::class, "idEvento", "idEvaluacion", "id", "id");
    }

    public function evaluacion() {
        return $this->hasOne( Evaluacion::class, 'idEvento', 'id');
    }
    public function cronograma() {
        return $this->hasOne( Cronograma::class, 'idEvento', 'id');
    }
    public function publicidades() {
        return $this->hasMany( Publicidad::class, 'idEvento', 'id');
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

    public function reservaciones(){
        return $this->hasMany(Reservacion::class, 'idEvento', 'id');
    }

    public function programasEducativos(){
        return $this->belongsToMany(ProgramaEducativo::class, "eventos_programaeducativos", "idEvento", "idProgramaEducativo");
    }

    public function aviso(){
        return $this->hasOne(Aviso::class, 'idEvento', 'id');
    }
    public function respuesta(){
        return $this->belongsTo(Respuesta::class, 'idRespuesta', 'id');
    }

    public static function encontrarPor($anio, $mes){
        return self::whereHas('reservaciones', function ($query) use ($anio, $mes) {
            $query->whereYear('inicio', '=', $anio)
                  ->whereMonth('inicio', '=', $mes);
        })
        ->with([ 
            'reservaciones.usuario', 
            'reservaciones.espacio', 
            'reservaciones.estado'
        ]);
    }

    /**
     * Fetches any event that have yet to be evaluated:
     * `evento.idEstado === EstadoEnum::aceptado && evento.fin < today`
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getUnevaluatedEvents(){
        return Evento
            ::where("idEstado", EstadoEnum::aceptado)
            ->whereDate("fin", "<", new \DateTime())
            ->with(['evaluacion', 'usuario'])
            ->get();
    }
}
