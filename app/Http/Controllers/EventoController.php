<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use App\Models\Cronograma;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Eventos_ProgramaEducativos;
use App\Models\Difusion;
use App\Models\Publicidad;
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
use App\Mail\Mailer;
use App\Mail\Mail;
use App\Mail\MailService;
use App\Models\Enums\RolEnum;
use App\Models\Aviso;

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
        $includeEstado = $request->query("estado");
        $orderByNombre = $request->query("porAlfabetico");
        $orderByCreatedAt = $request->query("porFechaEnvio");
        $eventName = $request->query("nombre");
        $startYearMonth = $request->query("inicio");

        $eventos = Evento::where($queryItems);
        if ($eventName){
            $eventos = $this->getEventsByName($request, $eventos);
        }
        if ($startYearMonth){
            $eventos = $eventos->where(\DB::raw("DATE_FORMAT(inicio, '%Y-%m')"), "=", $startYearMonth);
        }
        if ($orderByCreatedAt){
            $eventos = $eventos->orderBy("created_at");
        }
        if ($orderByNombre){
            $eventos = $eventos->orderBy("nombre");
        }
        if ($includeEvaluacion) {
            $eventos = $eventos->with("evaluacion");
        }

        $eventos->with(['programasEducativos', 'usuario', 'estado']);

        $dontPaginate = $startYearMonth;

        return new EventoCollection($eventos->paginate($dontPaginate ? 900 : 5)->appends($request->query())); 
    }    
    
    public function getEventsByName(Request $request, Builder|Evento $eventos){
        if ($request->has('nombre')) {

            /*
                Los eventos se filtran utilizando el método Model Collection->filter()
            */
            $searchString = $request->query('nombre');
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
        $evento = Evento::with(['estado', 'tipo', 'modalidad', 'usuario', 'programasEducativos', 'reservaciones.espacio', 'evaluacion.evidencias'])->find($idEvento);

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

        try{

            $nonNullData = array_filter($request->all(), function ($value) {
                return !is_null($value);
            });

            $event = Evento::create($nonNullData);
            $event->save();
            
            Aviso::create([
                "visto" => 0,
                "idUsuario" => null,
                "idEvento" => $event->id,
                "idEstado" => EstadoEnum::en_revision,
                "idTipoAviso" => TipoAvisoEventEnum::evento_nuevo
            ]);
            
            $idEvento = $event->id;
            $this->storeCronograma($request, $idEvento);
            $this->storePublicidad($request, $idEvento);
            
            $programas = json_decode($request->input("programas"), true);
            foreach ($programas as $programa){
                \DB::insert("INSERT INTO eventos_programaeducativos (idEvento, idProgramaEducativo) VALUES (?, ?)", [$event->id, $programa["id"]]);
            }
            
            $reservaciones = json_decode($request->input("reservaciones"), true);
            foreach ($reservaciones as $reservacion) {
                Reservacion::findOrFail($reservacion["id"])
                            ->update([
                                "idEstado" => EstadoEnum::evaluado,
                                "idEvento" => $event->id,
                            ]);
            }
            
            $organizer = User::findOrFail($request->input("idUsuario"));
            
            $coordinators = User::where("idRol", RolEnum::coordinador)->get();
            foreach($coordinators as $coordinator){
                Mailer::sendEmail(to: $coordinator, mail: MailService::GetEventNewMail($event, $organizer));
            }
            
            $code = 201;
            \DB::commit();


        } catch (\Throwable $ex) {
            $message = $ex->getMessage();
            \DB::rollBack();
        }finally{
            
            return response()->json([
                'message' => $message,
                'data' => $request->input("difusion")], $code);
            } 
            
            //return response()->json($request);
        } 

    private function storeCronograma(Request $request, int $idEvento){
        if (!$request->hasFile('cronograma')) {
            return;
        }

        $cronograma = $request->file("cronograma");

        if (!$cronograma->isValid()) {
            return response()->json(['error' => 'El archivo ' . $cronograma->getClientOriginalName() . 'no es válido.'], 400);
        }

        $blob = file_get_contents($cronograma->getRealPath());
        
        $document = new Cronograma;
        $document->archivo = $blob;
        $document->tipo = $cronograma->getMimeType();
        $document->nombre = $cronograma->getClientOriginalName();
        $document->idEvento = $idEvento;
        $document->save();
    }
    
    private function storePublicidad(Request $request, int $idEvento){
        if (!$request->hasFile('publicidad')) {
            return "a";
        }

        $publicidades = $request->file("publicidad");

        foreach ($publicidades as $publicidad){
            if (!$publicidad->isValid()) {
                return response()->json(['error' => 'El archivo ' . $publicidad->getClientOriginalName() . 'no es válido.'], 400);
            }
        }

        foreach ($publicidades as $publicidadArchivo){
            $blob = file_get_contents($publicidadArchivo->getRealPath());
            
            $publicidad = new Publicidad();
            $publicidad->archivo = $blob;
            $publicidad->tipo = $publicidadArchivo->getMimeType();
            $publicidad->nombre = $publicidadArchivo->getClientOriginalName();
            $publicidad->idEvento = $idEvento;
            $publicidad->save();
        }
    }
    
    
    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evento $evento)
    {
        $status = 500;
        $message = "Algo falló";
        
        \DB::beginTransaction();
        try{
            $nonNullData = array_filter($request->input("model"), function ($value) {
                return !is_null($value);
            });
            $originalIdEstado = $evento->idEstado;

            $evento->update($nonNullData);

            Aviso::notifyResponse(
                $request->input("idAviso"), 
                $evento, 
                $originalIdEstado
            );
            
            \DB::commit();
            
            $message = "Evento actualizado";
            
            $status = 200;
        } catch (\Throwable $ex){
            \DB::rollBack();
            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => $evento,
            ], $status);
        }
    } 
    
    private function changeEventStatus(Evento $event, EstadoEnum $newIdEstado) {

        $users = [];
        $mail = new Mail();
        

        switch ($newIdEstado){
            case EstadoEnum::aceptado:
                $mail = MailService::GetEventAcceptedMail(event: $event);
                $users = User::where("id", $event->idUsuario)->get();
                break;
            case EstadoEnum::rechazado:
                $mail = MailService::GetEventDeniedMail(event: $event);
                $users = User::where("id", $event->idUsuario)->get();
                break;
            default: 
                $users = User::where("idRol", RolEnum::coordinador)->get();
        }


        foreach($users as $user){
            Mailer::sendEmail(to: $user, mail: $mail);
        } 
        
    }

}
