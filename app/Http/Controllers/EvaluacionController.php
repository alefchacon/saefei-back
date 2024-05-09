<?php

namespace App\Http\Controllers;

use App\Http\Resources\EvaluacionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Evidencia;
use App\Models\Evento;
use App\Http\Resources\EvaluacionCollection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;


/**
 * Description of EvaluacionController
 *
 * @author Alejandro Chacón
 */


class EvaluacionController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function index(Request $request)	
    {
        return new EvaluacionCollection(Evaluacion::with('evidencias')->get());
        
    }    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Evaluacion $evaluacion)
    {
        return view('pages.evaluaciones.show', [
                'record' =>$evaluacion,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     */
    public function create(Request $request)
    {
        return new EvaluacionResource(Evaluacion::create($request->all()));
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {0;

        DB::beginTransaction();
        $code = 500;
        $evaluation = new Evaluacion;
        try{
            $evaluation = Evaluacion::create($request->all());
            $event = Evento::find($evaluation->idEvento);
            $event->idEstado = 3;
            $event->save();
            DB::commit();


            $message = 'Evaluación registrada';
            $code = 201;
        }catch (\Exception $ex) {
            $message = $ex->getMessage();
            \DB::rollBack();
        }finally{
            
            return response()->json([
                'message' => $message,
                'data' => new EvaluacionResource($evaluation)], $code);
        } 
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Evaluacion $evaluacion)
    {
		$eventos = Evento::all(['id']);

        return view('pages.evaluaciones.edit', [
            'model' => $evaluacion,
			"eventos" => $eventos,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Evaluacion $evaluacion)
    {
        $evaluacion->fill($request->all());

        if ($evaluacion->save()) {
            
            session()->flash('app_message', 'Evaluacion successfully updated');
            return redirect()->route('evaluaciones.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating Evaluacion');
            }
        return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Evaluacion $evaluacion)
    {
        if ($evaluacion->delete()) {
                session()->flash('app_message', 'Evaluacion successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Evaluacion');
            }

        return redirect()->back();
    }

    public function uploadEvidence(Request $request){
        DB::beginTransaction();
        try{
            $evidence = $request->input('Evidencia');

            
            if ($request->hasFile('Evidencia.archivo')) {
                $file = $request->file('Evidencia.archivo');
                if ($file->isValid()) {
                    $blob = file_get_contents($file->getPathname());
                    $evaluationId = $evidence['idEvaluacion'] ?? null;
                    
                    $document = new Evidencia;
                    $document->archivo = $blob;
                    $document->tipo = $evidence['tipo'];
                    $document->nombre = $evidence['nombre'];
                    $document->idEvaluacion = $evaluationId;
                    $document->save();
        
                    DB::commit();
                    return response()->json(['message' => 'File and evaluation ID uploaded successfully']);
                }
            }
            $message = 'Evaluación registrada';
            $code = 201;
        }catch (\Exception $ex) {
            $message = $ex->getMessage();
            \DB::rollBack();
        }finally{
            
            return response()->json([
                'message' => $message,
                'data'=> $evidence], $code);
        };
    }
}
