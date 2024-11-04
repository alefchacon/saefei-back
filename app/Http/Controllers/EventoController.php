<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoLightResource;
use App\Http\Resources\EventoResource;
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
        $returnAll = $request->query("todo");

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

        $eventos->with(['programasEducativos', 'usuario', 'reservaciones.actividades', 'reservaciones.espacio']);


        if ($returnAll){
            return new EventoCollection($eventos->get()); 
        } else {
            return EventoLightResource::collection($eventos->paginate(10)->appends($request->query())); 

        }
    }    
    
    public function getEventsByName(Request $request, Builder|Evento $eventos){
        if ($request->has('q')) {

            /*
                Los eventos se filtran utilizando el método Model Collection->filter()
            */
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
        $message = 'Evaluación registrada';

        

        \DB::beginTransaction();
        $event = new Evento;

        $test = "";
        $actividadesCorrespondientes = [];
        $reservacionIds = [];
        try{

            $nonNullData = array_filter($request->all(), function ($value) {
                return !is_null($value);
            });

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

            /*
            foreach ($reservaciones as $reservacion) {
                Reservacion::findOrFail($reservacion)
                            ->update([
                                "idEstado" => EstadoEnum::evaluado,
                                "idEvento" => $event->id,
                            ]);
            }*/

            $actividadesJson = $request->input("actividades");
            $actividades = json_decode($actividadesJson, true);
            
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
            
            
            $event->load("usuario");
            
            $mail = MailProvider::getEventMail(
                event: $event,
                type: TipoAvisoEventEnum::evento_nuevo
            );



            $coordinators = User::where("idRol", RolEnum::coordinador)->get();
            foreach($coordinators as $coordinator){
                MailService::sendEmail(
                    to: $coordinator, 
                    mail: $mail
                );
            }
            
            Aviso::create([
                "visto" => 0,
                "idEvento" => $event->id,
                "idTipoAviso" => TipoAvisoEventEnum::evento_nuevo
            ]);

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

        foreach ($archivos as $archivo){
            if (!$archivo->isValid()) {
                return response()->json(['error' => 'El archivo ' . $archivo->getClientOriginalName() . 'no es válido.'], 400);
            }
        }

        $tipoArchivo->getKey();
        foreach ($archivos as $archivo) {
            $path = $archivo->store("uploads", 'public');
            $archivoModel = new Archivo();
            $archivoModel->ruta = basename($path);
            $archivoModel->nombre = $archivo->getClientOriginalName();
            $archivoModel->idEvento = $idEvento;
            $archivoModel->idTipoArchivo = $tipoArchivo->value;
            $archivoModel->save();
        }
        
    }
    
    /**
     * The name of the Evento parameter MUST be "$evento", else it won't work.
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Evento $evento
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Evento $evento){
        $status = 500;
        $message = "Algo falló";
        
        \DB::beginTransaction();

        try{
            $nonNullData = array_filter($request->all(), function ($value) {
                return !is_null($value);
            });
            

            $evento->update($nonNullData);
            
            \DB::commit();
            
            $message = "Evento actualizado";
            
            $status = 200;
        } catch (\Throwable $ex){
            \DB::rollBack();
            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => new EventoResource($evento),
            ], $status);
        }
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
