<?php

namespace App\Models;

use App\Models\Enums\EstadoEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;
    protected $table = 'eventos';
    //public $timestamps = false;
    protected $fillable = [
        "nombre",
        "descripcion",
        "numParticipantes",
        "audiencias",
        "ambito",
        "eje",
        "tematicas",
        "pagina",

        "medios",
        "mediosNotificados",

        "constancias",

        "presidium",

        "decoracion",

        "requisitosCentroComputo",
        "requiereTransmisionEnVivo",
        
        "numParticipantesExternos",
        "requiereEstacionamiento",
        "requiereFinDeSemana",
        
        "adicional",

        "respuesta",
        "respondido",

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
    public function cambios(){
        return $this->hasMany(Cambio::class, 'idEvento', 'id');
    }

    public function programasEducativos(){
        return $this->belongsToMany(ProgramaEducativo::class, "eventos_programaeducativos", "idEvento", "idProgramaEducativo");
    }

    public function avisos(){
        return $this->hasMany(Aviso::class, 'idEvento', 'id');
    }
    public function respuesta(){
        return $this->belongsTo(Respuesta::class, 'idRespuesta', 'id');
    }

    public function archivos(){
        return $this->hasMany(Archivo::class, 'idEvento', 'id');
    }

    public static function scopeFindBy($query, $startYearMonth,){
        return $query->select('eventos.*')
        ->joinSub(
            Reservacion::select('idEvento')
                ->selectRaw('MIN(DATE_FORMAT(fecha, "%Y-%m-01")) as first_reservation_month')
                ->groupBy('idEvento'),
            'first_reservations',
            function ($join) {
                $join->on('eventos.id', '=', 'first_reservations.idEvento');
            }
        )
        ->whereRaw('DATE_FORMAT(first_reservations.first_reservation_month, "%Y-%m") = ?', [$startYearMonth]);
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

    public static function splitByReservations(Builder $events){
        return $eventsQuery = $events->get()
        ->flatMap(function ($event) {
            return $event->reservaciones->map(function ($reservation) use ($event) {
                $newEvent = $event->replicate();
                $newEvent->setRelation('reservaciones', collect([$reservation]));
                return $newEvent;
            });
        });
    }

    public function getCustomDirty()
    {
        $dirty = [];
    
        foreach ($this->getAttributes() as $key => $value) {
             if ($this->isDirty($key)) {
                 $original = $this->getOriginal($key);
    
                if (is_string($original)) {
                    $original = json_decode($original, true);
                }
    
                
                if (is_array($value) && is_array($original)) {
                    if ($value !== $original) {
                        $dirty[$key] = $value;
                    }
                } else {
                    
                    $dirty[$key] = $value;
                }
            }
        }
    
        return $dirty;
    }
    

}
