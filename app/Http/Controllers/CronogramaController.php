<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArchivoResource;
use App\Http\Resources\EventoResource;
use App\Http\Resources\AvisoCollection;
use App\Models\Aviso;
use App\Models\Cronograma;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CronogramaController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function show($id)
    {
        
        $cronograma = Cronograma::where("idEvento", "=", $id)->first();
        if (!$cronograma) {
            return response()->json(['message' => 'No existe ese cronograma'], 404);
        }
        
        return new ArchivoResource($cronograma);
    }  
}
