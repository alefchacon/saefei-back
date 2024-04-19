<?php

namespace App\Http\Controllers;

use App\Models\Publicidad;
use Illuminate\Http\Request;

class PublicidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publicidad = Publicidad::all();
        return response()->json($publicidad);
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
        $publicidad = new Publicidad();

    }

    /**
     * Display the specified resource.
     */
    public function show(Publicidad $publicidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publicidad $publicidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publicidad $publicidad)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publicidad $publicidad)
    {
        //
    }
}
