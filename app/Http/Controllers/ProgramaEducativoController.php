<?php

namespace App\Http\Controllers;

use App\Http\Resources\CatalogoResource;
use App\Http\Resources\CatalogoCollection;
use App\Models\ProgramaEducativo;
use Illuminate\Http\Request;

class ProgramaEducativoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $programas = ProgramaEducativo::all();
        return new CatalogoCollection($programas);
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
        $programa = new ProgramaEducativo();
        $programa->fill($request->all());
        $programa->save();

        $respuesta = [
            'message' => 'Programa educativo creado exitosamente',
            'programa' => $programa
        ];

        return response()->json($respuesta);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramaEducativo $programaEducativo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramaEducativo $programaEducativo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramaEducativo $programaEducativo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramaEducativo $programaEducativo)
    {
        //
    }
}
