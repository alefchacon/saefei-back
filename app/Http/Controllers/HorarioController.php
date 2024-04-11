<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $horarios = Horario::all();

        return response()->json($horarios);
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
        $horario = new Horario();
        $horario->fill($request->all());
        $horario->save();

        $data = [
            'message' => 'Horario creado exitosamente',
            'horario' => $horario
        ];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Horario $horario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Horario $horario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Horario $horario)
    {
        $horario->fill($request->all());
        $horario->update();

        $data = [
            'messge' => 'Horario modificado exitosamente',
            'horario' => $horario
        ];
        
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();

        $data = [
            'message' => 'Horario eliminado exitosamente',
            'horario' => $horario
        ];

        return response()->json($data);
    }
}
