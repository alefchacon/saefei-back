<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoCalendarioResource;
use App\Http\Resources\EventoLightResource;
use App\Http\Resources\EventoResource;
use App\Models\Cambio;
use App\Models\Cronograma;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Eventos_ProgramaEducativos;
use App\Models\Difusion;
use App\Models\Publicidad;
use App\Models\Archivo;
use App\Models\Respuesta;
use App\Models\User;
use App\Models\Reservacion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Enums\EstadoEnum;
use App\Filters\EventoFilter;
use App\Http\Resources\EventoCollection;
use Exception;
use App\Mail\MailService;
use App\Mail\Mail;
use App\Mail\MailProvider;
use App\Models\Enums\RolEnum;
use App\Models\Enums\TiposArchivosEnum;
use App\Models\Aviso;
use App\Models\Actividad;


class EventoController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function index(Request $request)
    {
        $filter = new EventoFilter();
        $queryItems = $filter->transform($request);
        
        $includeEvaluacion = $request->query("evaluacion");
        $includeEvidences = $request->query("evidencias");
        $includeEstado = $request->query("estado");
        $orderBy = $request->query("orden");
        $eventName = $request->query("q");
        $startYearMonth = $request->query("fecha");
        $userEvents = $request->query("delUsuario");
        $dontPaginate = $request->query("sinPaginar");
        $forCalendar = $request->query("paraCalendario");
        

        $eventos = Evento::where($queryItems);
        if ($eventName){
            $eventos = $this->getEventsByName($request, $eventos);
        }
        if ($startYearMonth){
            $eventos = Evento::findBy($startYearMonth);
        }
        if ($orderBy == "fecha"){
            $eventos = $eventos->orderByDesc("created_at");
        }
        if ($orderBy == "alfabetico"){
            $eventos = $eventos->orderBy("nombre");
        }
        if ($includeEvaluacion) {
            $eventos = $eventos->with("evaluacion");
        }
        if ($includeEvidences) {
            $eventos = $eventos->with("evidencias");
        }
        if ($userEvents) {
            $organizer = User::findByToken($request);
            $eventos = $eventos->where("idUsuario", "=", $organizer->id);
        }
        
        $eventos->with(['programasEducativos', 'usuario', 'reservaciones.actividades', 'reservaciones.espacio']);

        if ($forCalendar) {
            $eventos = Evento::splitByReservations($eventos);
        }


        if ($dontPaginate){
            return EventoLightResource::collection($eventos); 
        } else {
            return EventoLightResource::collection($eventos->paginate(10)->appends($request->query())); 

        }
    }   
    
    public function getCalendarEvents(Request $request){
        $filter = new EventoFilter();
        $queryItems = $filter->transform($request);
        
        $startYearMonth = $request->query("fecha");
        $eventos = Evento::where($queryItems);

        $eventos = Evento::findBy($startYearMonth);
        
        $eventos->with([ 'reservaciones.actividades', 'reservaciones.espacio']);
        $eventos = Evento::splitByReservations($eventos);



        return EventoCalendarioResource::collection($eventos); 
    }
    
    public function getEventsByName(Request $request, Builder|Evento $eventos){
        if ($request->has('q')) {
            $searchString = $request->query('q');
            $modelEvents = $eventos->get();
            $filteredEvents = $this->filterEventsByName($modelEvents, $searchString);

            /*
                Este tipo de colecciones NO se puede paginar, entonces se debe convertir
                a un tipo que sí permita la paginación: Builder Collection.
            */
            $ids = $filteredEvents->pluck("id")->toArray();
            $builderEvents = Evento::whereIn("id", $ids);
            return $builderEvents;
        } else {
            return Evento::query()->toBase();
        }
    }

    
    private function filterEventsByName($eventos, $searchString, $threshold = 3)
    {
        return $eventos->filter(function ($event) use ($searchString, $threshold) {
            $mainString = 
                $event->nombre . 
                $event->usuario->nombres . " " . 
                $event->usuario->apellidoPaterno . " " .
                $event->usuario->apellidoMaterno;

            $targetString = $searchString;
            $mainStringLength = strlen($mainString);
            $targetStringLength = strlen($targetString);
        
            for ($i = 0; $i <= $mainStringLength - $targetStringLength; $i++) {
                $substring = substr($mainString, $i, $targetStringLength);
                $levDistance = levenshtein($substring, $targetString);
        
                if ($levDistance <= $threshold) {
                    return true;
                }
            }
        
            return false;
        });
    }

    function containsSimilarSubstring($mainString, $targetString, $threshold = 2) {
        $mainStringLength = strlen($mainString);
        $targetStringLength = strlen($targetString);
    
        for ($i = 0; $i <= $mainStringLength - $targetStringLength; $i++) {
            $substring = substr($mainString, $i, $targetStringLength);
            $levDistance = levenshtein($substring, $targetString);
    
            if ($levDistance <= $threshold) {
                return true;
            }
        }
    
        return false;
    }

    public function getByMonth(Request $request)
    {
        $anio = $request->input('year');
        $mes = $request->input('month');


        $events = Evento::
                        whereYear("eventos.inicio", "=", $anio)
                        ->whereMonth("eventos.inicio", "=", $mes)
                        ->where("eventos.idEstado", EstadoEnum::aceptado)
                        ->with(["reservaciones.espacio", "modalidad"]);


        return new EventoCollection($events->paginate());
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, int $idEvento)
    {
        $evento = Evento::with([
            'estado', 
            'tipo', 
            'modalidad', 
            'usuario', 
            'programasEducativos', 
            'reservaciones.espacio', 
            'reservaciones.actividades', 
            'archivos',
        ])->find($idEvento);

        $user = User::findByToken($request);

        if (isset($user) && ($user->isCoordinator() || $user->id === $evento->idUsuario)){
            $evento->load("cambios");
        }

        if (!$evento) {
            return response()->json(['message' => 'No existe ese evento'], 404);
        }

        
        return new EventoResource($evento);
    }    

   /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $code = 500;
        $message = 'Algo salió mal';


        \DB::beginTransaction();
        $event = new Evento;

        $actividadesCorrespondientes = [];
        $reservacionIds = [];
        try{

            $nonNullData = array_filter($request->all(), function ($value) {
                    return $value !== null && $value !== "null";
                }
            );

            $event = Evento::create($nonNullData);
            $event->save();

            $idEvento = $event->id;
            $this->storeArchivo(
                $request, 
                $idEvento, 
                TiposArchivosEnum::PUBLICIDAD
            );
            $this->storeArchivo(
                $request, 
                $idEvento, 
                TiposArchivosEnum::CRONOGRAMA
            );
        
            $programas = json_decode($request->input("programas"), true);
            foreach ($programas as $programa){
                \DB::insert("INSERT INTO eventos_programaeducativos (idEvento, idProgramaEducativo) VALUES (?, ?)", [$event->id, $programa]);
            }
            
            $reservacionIds = json_decode($request->input("reservaciones"), true);

            
            foreach ($reservacionIds as $reservacionId) {
                Reservacion::findOrFail($reservacionId)
                            ->update([
                                "idEstado" => EstadoEnum::evaluado,
                                "idEvento" => $event->id,
                            ]);
            }

            $actividadesJson = $request->input("actividades");
            $actividades = json_decode($actividadesJson, true);
            
            if (isset($actividades)){
                    foreach($reservacionIds as $reservacionId){
                        $actividadesCorrespondientes = array_filter(
                        $actividades, 
                        function($actividad) use ($reservacionId) {
                            return $actividad['idReservacion'] === $reservacionId;
                        }
                    );
                    foreach ($actividadesCorrespondientes as $actividad){
                        Actividad::create($actividad);
                    }
                }
            }
            
            
            $event->load("usuario");
            
            Aviso::notifyNewEvent($event);
            $message = 'Evento registrado';
            $code = 201;
            \DB::commit();


        } catch (\Throwable $ex) {
            $message = $ex->__tostring();
            \DB::rollBack();
        }finally{
            
            return response()->json([
                'message' => $message,
                'data' => $actividadesCorrespondientes
            ], $code);
        } 
            
            //return response()->json($request);
    } 

    function find($array, $callback) {
        $filtered = array_filter($array, $callback);
        return !empty($filtered) ? array_values($filtered)[0] : null;
    }

    private function storeArchivo(Request $request, int $idEvento, TiposArchivosEnum $tipoArchivo){
        if (!$request->hasFile($tipoArchivo->getKey())) {
            return "no files found!";
        }            
        $archivos = $request->file($tipoArchivo->getKey());

        Archivo::bulkCreate($idEvento, $tipoArchivo, $archivos);
        
    }
    
    /**
     * The name of the Evento parameter MUST be "$evento", else it won't work.
     * Also, this method will NOT not work with FormData for some reason. Which
     * means updating the files has to be done in multiple calls :/
     */
    public function update(Request $request, Evento $evento){
        $status = 500;
        $message = "";


        \DB::beginTransaction();
        
        try{
            $editor = User::findByToken($request);
            
            if (!$editor){
                return response()->json(["message" => "Token inválido"], 401);
            }
    
            $canEdit = 
                $editor->isCoordinator() 
                || $editor->id === $evento->idUsuario;
            
            if (!$canEdit){
                return response()->json(["message" => "No tiene permiso para realizar esta operación"], 403);
            }

            $nonNullData = array_filter($request->all(), function ($value) {
                return $value !== null && $value !== '';
            });
            $evento->fill($nonNullData);
            $evento->save();
            
            $this->storeArchivo(
                $request, 
                $evento->id, 
                TiposArchivosEnum::PUBLICIDAD
            );
            $this->storeArchivo(
                $request, 
                $evento->id, 
                TiposArchivosEnum::CRONOGRAMA
            );
            
            /*
            $updatedColumns = array_keys($evento->getCustomDirty());
            if (!empty($updatedColumns)){
                Cambio::create([
                    "columnas" => json_encode($updatedColumns),
                    "idEvento" => $evento->id,
                    "idUsuario" => $editor->id,
                ]);
                $message = Aviso::notifyEventUpdate($evento, $editor);
            }*/

            $message = "fuck";
            
            \DB::commit();

            
            $status = 200;
        } catch (\Throwable $ex) {
            $message = $ex->__tostring();
            \DB::rollBack();
        }
        return response()->json([
            'message' => $request->input("nombre"),
        ], $status);
    } 

    private static function handleEventMail(
        Evento $event, 
        int $originalIdEstado
    ){
        $replyingToEventOrganizer = 
            $originalIdEstado !== $event->idEstado;

        if (!$replyingToEventOrganizer){
            return;
        }

        $type = TipoAvisoEventEnum::tryFrom($event->idEstado);
        
        $mail = MailProvider::getEventMail(
            event: $event, 
            type: $type
        );

        $event->load('usuario');
        MailService::sendEmail(
            to: $event->usuario,
            mail: $mail 
        );
    }

}
