<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use App\Http\Requests\StoreEstadoRequest;
use App\Http\Requests\UpdateEstadoRequest;

class EstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = Estado::all();
        return response()->json($estados);
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
    public function store(StoreEstadoRequest $request)
    {
        $estado = new Estado();
        $estado->fill($request->all());
        $estado->save();

        $response = [
            'message' => 'Estado creado existosamente',
            'estado' => $estado
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(Estado $estado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estado $estado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstadoRequest $request, Estado $estado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Estado $estado)
    {
        //
    }
}
