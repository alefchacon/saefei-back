<?php

namespace App\Http\Controllers;

use App\Models\Modalidad;
use Illuminate\Http\Request;

class ModalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $modalidades = Modalidad::all();
        return response()->json($modalidades);
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
        $modalidad = new Modalidad();
        $modalidad->fill($request->all());
        $modalidad->save();

        $respuesta = [
            'message' => 'Modalidad creada exitosamente',
            'modalidad' => $modalidad
        ];

        return response()->json($respuesta);
    }

    /**
     * Display the specified resource.
     */
    public function show(Modalidad $modalidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modalidad $modalidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modalidad $modalidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modalidad $modalidad)
    {
        //
    }
}
