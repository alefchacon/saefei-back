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
        $orderByNombre = $request->query("porAlfabetico");
        $orderByCreatedAt = $request->query("porFechaEnvio");
        $eventName = $request->query("nombre");
        $startYearMonth = $request->query("inicio");
        $returnAll = $request->query("todo");

        $eventos = Evento::where($queryItems);
        if ($eventName){
            $eventos = $this->getEventsByName($request, $eventos);
        }
        if ($startYearMonth){
            $eventos = $eventos->where(\DB::raw("DATE_FORMAT(inicio, '%Y-%m')"), "=", $startYearMonth);
        }
        if ($orderByCreatedAt){
            $eventos = $eventos->orderByDesc("created_at");
        }
        if ($orderByNombre){
            $eventos = $eventos->orderBy("nombre");
        }
        if ($includeEvaluacion) {
            $eventos = $eventos->with("evaluacion");
        }
        if ($includeEvidences) {
            $eventos = $eventos->with("evidencias");
        }

        $eventos->with(['programasEducativos', 'usuario']);


        if ($returnAll){
            return new EventoCollection($eventos->get()); 
        } else {
            return EventoLightResource::collection($eventos->paginate(5)->appends($request->query())); 

        }
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

        $test = "";
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
            $test = $this->storeArchivo(
                $request, 
                $idEvento, 
                TiposArchivosEnum::CRONOGRAMA
            );
        
            $programas = json_decode($request->input("programas"), true);
            foreach ($programas as $programa){
                \DB::insert("INSERT INTO eventos_programaeducativos (idEvento, idProgramaEducativo) VALUES (?, ?)", [$event->id, $programa]);
            }
            
            $reservaciones = json_decode($request->input("reservaciones"), true);
            foreach ($reservaciones as $reservacion) {
                Reservacion::findOrFail($reservacion)
                            ->update([
                                "idEstado" => EstadoEnum::evaluado,
                                "idEvento" => $event->id,
                            ]);
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
                "idUsuario" => null,
                "idEvento" => $event->id,
                "idEstado" => EstadoEnum::en_revision,
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
                'data' => $test], $code);
            } 
            
            //return response()->json($request);
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
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evento  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Evento $event)
    {
        $status = 500;
        $message = "Algo falló";
        
        \DB::beginTransaction();

        //return response()->json(["sadf" => $request->input("model")["id"]]);

        try{
            $nonNullData = array_filter($request->input("model"), function ($value) {
                return !is_null($value);
            });
            
            $event = Evento::findOrFail($request->input("model")["id"]);

            $originalIdEstado = $event->idEstado;

            $event->update($nonNullData);

            self::handleEventMail(
                event: $event,
                originalIdEstado: $originalIdEstado
            );

            Aviso::notifyResponse(
                $request->input("idAviso"), 
                $event, 
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
                'data' => $event,
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
