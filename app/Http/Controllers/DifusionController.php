<?php

namespace App\Http\Controllers;

use App\Models\Difusion;
use Illuminate\Http\Request;

class DifusionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $difusiones = Difusion::all();
        return response()->json($difusiones);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $difusion = new Difusion();
        $difusion->fill($request->all());
        $difusion->save();

        $respuesta = [
            'message' => 'Difusion creada exitosamente',
            'difusion' => $difusion  
        ];

        return response()->json($respuesta);
    }

    /**
     * Display the specified resource.
     */
    public function show(Difusion $difusion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Difusion $difusion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Difusion $difusion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Difusion $difusion)
    {
        //
    }
}
