<?php

namespace App\Http\Controllers;

use App\Http\Resources\SolicitudEspacioCollection;
use App\Http\Resources\SolicitudEspacioResource;
use App\Models\Espacio;
use App\Models\User;
use App\Models\SolicitudEspacio;
use Illuminate\Http\Request;
use App\Filters\SolicitudEspacioFilter;


class SolicitudEspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new SolicitudEspacioFilter();
        $queryItems = $filter->transform($request);
        
        $solicitudes = SolicitudEspacio::where($queryItems)->with([ 
            "usuario", 
            "estado", 
            "espacio",
        ])->get();


        return new SolicitudEspacioCollection($solicitudes);
    }
    public function getAvailableReservations(Request $request)
    {
        $idUsuario = $request->input("idUsuario");

        $solicitudes = SolicitudEspacio
            ::where("idUsuario", "=", $idUsuario)
            ->where("idEstado", "=", 2)
            ->with([
                "usuario", 
                "estado", 
                "espacio", 
            ])->get();

        return new SolicitudEspacioCollection($solicitudes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "Algo falló.";
        $status = 500;
        $data = "";

        $solicitud = new SolicitudEspacio();
        try {

            $solicitud->fill($request->all());

            $solicitud->save();
            
            $solicitud->with([
                "usuario", 
                "estado", 
                "espacio", 
            ])->get();
            
            $message = 'Solicitud realizada. Espere confirmacion';
            $status = 201;
            $data = new SolicitudEspacioResource($solicitud);

        } catch (\Exception $ex){
            //No regresar excepciones: cambiar a mensaje personalizado y ambiguo.
            $message = $ex->getMessage();
        } finally {
            
            return response()->json([
                'message' => $message,
                'data' => $data,
            ], $status);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(SolicitudEspacio $solicitudEspacio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SolicitudEspacio $solicitudEspacio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $status = 500;
        $message = "Algo falló";
        try{
            $model = SolicitudEspacio::findOrFail($request->id);
            $model->update($request->all());
            //$model->load("espacio");
            $message = "Reservación actualizada";
            $status = 200;
        } catch (\Exception $ex){
            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => isset($model) ? new SolicitudEspacioResource($model) : null,
                'payload' => $request->toArray()
            ], $status);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SolicitudEspacio $solicitudEspacio)
    {
        //
    }
}
