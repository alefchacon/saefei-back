<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
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
    public function index(Request $request)
    {

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
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Evento $evento)
    {

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

    }
}
