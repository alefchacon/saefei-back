<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Usuario;
use App\Models\Modalidade;
use App\Models\Estado;
use App\Models\Tipo;
use App\Filters\EventoFilter;
use App\Http\Resources\EventoCollection;
use Exception;


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

        $eventos = Evento::where($queryItems);

        if ($eventName){
            $eventos = $this->getEventsByName($request, $eventos);
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
        if ($includeEstado) {
            $eventos = $eventos->with("estado");
        }
        

        return new EventoCollection($eventos->paginate(5)->appends($request->query())); 
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
        $evento = Evento::with(['estado', 'evaluacion', 'tipo', 'modalidad', 'usuario'])->find($id);
        if (!$evento) {
            return response()->json(['message' => 'No existe ese evento'], 404);
        }

        return new EventoResource($evento);

    }    


    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$usuario = Usuario::all(['id']);
		$modalidades = Modalidade::all(['id']);
		$estados = Estado::all(['id']);
		$tipos = Tipo::all(['id']);

        return view('pages.eventos.create', [
            'model' => new Evento,
			"usuario" => $usuario,
			"modalidades" => $modalidades,
			"estados" => $estados,
			"tipos" => $tipos,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $model=new Evento;
        $model->fill($request->all());

        if ($model->save()) {
            
            session()->flash('app_message', 'Evento saved successfully');
            return redirect()->route('eventos.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving Evento');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $status = 500;
        $message = "Algo falló";
        try{
            $model = Evento::with("estado")->findOrFail($request->id);
            $model->update($request->all());
            
            $status = 200;
        } catch (Exception $ex){
            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => 'Evaluation updated successfully',
                'data' => new EventoResource($model),
            ], $status);
        }

    }    /**
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
        try{
            $model = Evento::findOrFail($evento->id);
            $model->update($request->all());
            $model->load("estado");
            $message = "Evento actualizado";
            $status = 200;
        } catch (Exception $ex){
            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => new EventoResource($model),
                'payload' => $evento->toArray()
            ], $status);
        }
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Evento $evento)
    {
        if ($evento->delete()) {
                session()->flash('app_message', 'Evento successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Evento');
            }

        return redirect()->back();
    }
}
