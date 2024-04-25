<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
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

/**
 * Description of EventoController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */

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

        $eventos = Evento::where($queryItems);
        
        
        if ($includeEvaluacion) {
            $eventos = $eventos->with("evaluacion");
        }
        if ($includeEstado) {
            $eventos = $eventos->with("estado");
        }
        

        return new EventoCollection($eventos->paginate()->appends($request->query())); 
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
            $message = "El evento ha sido " . $model->estado->nombre;
            $status = 200;
        } catch (Exception $ex){
            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => new EventoResource($model),
                'payload' => $request->toArray()
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
