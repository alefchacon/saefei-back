<?php

namespace App\Http\Controllers;

use App\Http\Resources\EvaluacionResource;
use App\Http\Resources\EventoResource;
use App\Mail\MailService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Evidencia;
use App\Models\Aviso;
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
    public function show($idEvento)
    {
        $evaluacion = Evaluacion::where("idEvento", "=", $idEvento)->first();
        if (!$evaluacion){
            return response()->json(["message" => "No se encontró la evaluación"], 404);
        }
        return new EvaluacionResource($evaluacion);
    }    
    
    public function store(Request $request)
    {0;

        DB::beginTransaction();
        $code = 500;
        $evaluation = new Evaluacion;
        try{
            $evaluation = Evaluacion::create($request->all());
            $this->storeEvidence($request, $evaluation->id);

            $event = Evento::find($evaluation->idEvento);
            $event->idEstado = 3;
            $event->save();
            $event->load(['evaluacion', 'usuario']);

            MailService::sendEvaluationNewMail($event);

            Aviso::where("idEvento", "=", $event->id)
                 ->update([
                    "avisarUsuario" => 0,
                    "avisarStaff" => 1
                 ]);


            DB::commit();


            $message = new EventoResource($event);
            $code = 201;
        }catch (\Exception $ex) {
            $message = $ex->getMessage();
            \DB::rollBack();
        }finally{
            
            return response()->json([
                'message' => $message,
                'data' => $request->hasFile('evidencias')], $code);
        } 
    } 

    private function storeEvidence(Request $request, int $idEvaluacion){
        if (!$request->hasFile('evidencias')) {
            return "a";
        }

        $evidencias = $request->file("evidencias");

        foreach ($evidencias as $evidencia){
            if (!$evidencia->isValid()) {
                return response()->json(['error' => 'El archivo ' . $evidencia->getClientOriginalName() . 'no es válido.'], 400);
            }
        }

        foreach ($evidencias as $evidenciaArchivo){
            $blob = file_get_contents($evidenciaArchivo->getRealPath());
            
            $evidencia = new Evidencia();
            $evidencia->archivo = $blob;
            $evidencia->tipo = $evidenciaArchivo->getMimeType();
            $evidencia->nombre = $evidenciaArchivo->getClientOriginalName();
            $evidencia->idEvaluacion = $idEvaluacion;
            $evidencia->save();
        }
    }
    
    
    /**
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
