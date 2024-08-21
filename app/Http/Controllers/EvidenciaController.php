<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArchivoCollection;
use App\Http\Resources\EventoResource;
use App\Http\Resources\EvidenciaCollection;
use App\Http\Resources\EvidenciaResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Evidencia;

use App\Filters\EventoFilter;
use App\Http\Resources\EventoCollection;
use Exception;


class EvidenciaController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function getEvidencesFor(Request $request)
    {
        $idEvaluaciones = $request->input("idEvaluaciones");
        $response = array_flip($idEvaluaciones);
        
        foreach ($idEvaluaciones as $idEvaluacion){
            $response[$idEvaluacion] = new EvidenciaCollection(Evidencia::where("idEvaluacion", "=", $idEvaluacion)->get());
        }

        return response()->json($response);
    }    
    
    
    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show($idEvaluacion)
    {
        $evidencia = Evidencia::where("idEvaluacion", "=", $idEvaluacion)->get();
        return new ArchivoCollection($evidencia);
    }    


       /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            
            if (!$request->hasFile('archivos')) {
                return response()->json(['error' => 'Debe subir al menos un archivo como evidencia.'], 400);
            }
            
            $files = $request->file('archivos');

            foreach ($files as $file){
                if (!$file->isValid()) {
                    return response()->json(['error' => 'El archivo ' . $file->getClientOriginalName() . 'no es válido.'], 400);
                }
            }

            foreach ($files as $file){

                $blob = file_get_contents($file->getRealPath());
                $idEvaluacion = $request->input('idEvaluacion');
                
                $document = new Evidencia;
                $document->archivo = $blob;
                $document->tipo = $file->getMimeType();
                $document->nombre = $file->getClientOriginalName();
                $document->idEvaluacion = $idEvaluacion;
                $document->save();
            }

            return response()->json(['message' => 'Evidencia registrada'], 201);

            
        }catch (Exception $ex) {
            $message = $ex->getMessage();
        }
    } 


}
