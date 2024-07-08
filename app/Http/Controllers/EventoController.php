<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use App\Http\Resources\SolicitudEspacioCollection;
use App\Models\Cronograma;
use App\Models\Eventos_ProgramaEducativos;
use App\Models\Difusion;
use App\Models\Publicidad;
use App\Models\User;
use App\Models\SolicitudEspacio;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Modalidade;
use App\Models\Estado;
use App\Models\Tipo;
use App\Filters\EventoFilter;
use App\Http\Resources\EventoCollection;
use Exception;
use App\Mail\Mailer;
use App\Mail\Mail;
use App\Mail\MailFactory;
use App\Models\Enums\EstadoEnum;
use App\Models\Enums\RolEnum;

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
        $orderByCoordinatorNotice = $request->query("porAvisosCoordinador");
        $orderByUserNotice = $request->query("porAvisosUsuario");

        $eventos = Evento::where($queryItems)->with(['programasEducativos', 'usuario']);
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
        if ($orderByCoordinatorNotice) {
            $eventos = $eventos->orderByDesc("avisarCoordinador");
        }
        if ($orderByUserNotice) {
            $eventos = $eventos->orderByDesc("avisarUsuario");
        }
        if ($includeEvaluacion) {
            $eventos = $eventos->with("evaluacion");
        }
        if ($includeEstado) {
            $eventos = $eventos->with("estado");
        }

        if ($startYearMonth){

            return new EventoCollection($eventos->paginate(1000)->appends($request->query())); 
        }

        return new EventoCollection($eventos->paginate(5)->appends($request->query())); 
        //return $eventos->paginate(5)->appends($request->query()); 
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
            $mainString = $event->nombre;
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

    public function getEventosPorMes(Request $request)
    {
        $anio = $request->input('year');
        $mes = $request->input('month');


        $events = Evento::encontrarPor($anio, $mes);


        return new EventoCollection($events->paginate());
    }
    
    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $evento = Evento::with(['estado', 'evaluacion.evidencias', 'tipo', 'modalidad', 'usuario', 'solicitudesEspacios.espacio'])->find($id);
        if (!$evento) {
            return response()->json(['message' => 'No existe ese evento'], 404);
        }

        /*
        $evidencia = \DB::select("SELECT * FROM evidencias WHERE id = ?", [1]);
        $base64Data = base64_encode($evidencia[0]->archivo);
        return $base64Data;
        */
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
            
            //$event = Evento::create($request->all() );
            //$event->save();
            
           // $programas = json_decode($request->input("programas"), true);
            
           /*
            foreach ($programas as $programa){
                /*
                Uso una consulta parametrizada en lugar de métodos de Eloquent
                por que, por alguna razón, PHP no reconoce la existencia de 
                la clase Eventos_ProgramaEducativos, pese a que ya se importó
                y todo. 
                
                El error que arroja es el siguiente. Para obtenerlo, el try-catch
                debe atrapar \Error en lugar de \Exception. 
                
                "Class "App\Models\Eventos_ProgramaEducativos" not found"
                
                */
                
                
                /*
                \DB::insert("INSERT INTO eventos_programaeducativos (idEvento, idProgramaEducativo) VALUES (?, ?)", [$event->id, $programa["id"]]);
                
                
            }
            */
            
            //RESERVACIONES
            /*
            $reservaciones = json_decode($request->input("reservaciones"), true);
            foreach ($reservaciones as $reservacion) {
                $reservationModel = SolicitudEspacio::findOrFail($reservacion["id"]);
                $reservationModel->idEstado = 3;
                $reservationModel->save();
            }
            */

            /*
            $organizer = User::findOrFail($request->input("idUsuario"));
            Mailer::sendEmail(to: $organizer);
            
            $coordinators = User::where("idRol", 5)->get();
            foreach($coordinators as $coordinator){
                Mailer::sendEmail(to: $coordinator);
            }
            */
            //$message  =$this->storePublicidad($request);
            //$message =$request->file("difusion");

            if ($request->hasFile('cronograma')) {
            
                $cronograma = new Cronograma();
                $file = $request->file('cronograma');
                if ($file->isValid()) {
                    $blob = file_get_contents($file->getRealPath());
                    
    
                    
                    $cronograma->archivo = $blob;
                    $cronograma->tipo = $file->getMimeType();
                    $cronograma->nombre = $file->getClientOriginalName();
                    $cronograma->idEvento = 1;
                    $cronograma->save();
                    
        
                }
            }
    

            $message = $request->input('cronograma');

            $code = 201;
            \DB::commit
            
            ();
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            \DB::rollBack();
        }finally{
            
            return response()->json([
                'message' => $message,
                'data' => $request->input("difusion")], $code);
            } 
            
            //return response()->json($request);
        } 
    
    private function storePublicidad(Request $request){
        if (!$request->hasFile('difusion')) {
            return "a";
        }

        $difusiones = $request->file("difusion");

        foreach ($difusiones as $difusion){
            if (!$difusion->isValid()) {
                return response()->json("Los archivos no pudieron procesarse", 500);
            }
        }


        foreach ($difusiones as $file){
            $publicidad = new Publicidad();
            $blob = file_get_contents($file->getRealPath());
            $publicidad->archivo = $blob;
            $publicidad->tipo = $file->getMimeType();
            $publicidad->nombre = $file->getClientOriginalName();
            $publicidad->idEvento = 1;
            $publicidad->save();
        }
        return "b";
    }
    
    
    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Evento $evento)
    {
        $status = 500;
        $message = "Algo falló";

        $isReply = $request->query("respuesta");

        
        \DB::beginTransaction();
        try{
            
            $evento->update($request->all());
            
            if ($isReply) {
                    
                $estado = EstadoEnum::tryFrom(
                    $request->input("idEstado")
                );

                $this->changeEventStatus($evento, $estado);
                 
                $evento->update(["avisarUsuario" => 1]);
            }
            
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
                $mail = MailFactory::GetEventAcceptedMail(event: $event);
                $users = User::where("id", $event->idUsuario)->get();
                break;
            case EstadoEnum::rechazado:
                $mail = MailFactory::GetEventDeniedMail(event: $event);
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
