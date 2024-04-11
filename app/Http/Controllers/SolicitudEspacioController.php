<?php

namespace App\Http\Controllers;

use App\Models\Espacio;
use App\Models\Horario;
use App\Models\SolicitudEspacio;
use Illuminate\Http\Request;

class SolicitudEspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $solicitudes = SolicitudEspacio::all();
        return response()->json($solicitudes);
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
        $solicitud = new SolicitudEspacio();
        $solicitud->fill($request->all());
        $solicitud->save();

        $espacio = Espacio::find($request->idEspacio);
        $horario = Horario::find($request->idHorario);
        
        $response = [
            'mesage' => 'Solicitud realizada. Espere confirmacion',
            'solicitud' => $solicitud->respuesta,
            'espacio' => $espacio->nombre,
            'horario' => $horario->inicio
        ];

        return response()->json($response);
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
    public function update(Request $request, SolicitudEspacio $solicitudEspacio)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SolicitudEspacio $solicitudEspacio)
    {
        //
    }
}
