<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use App\Http\Resources\ArchivoCollection;
use App\Models\Aviso;
use App\Models\Publicidad;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PublicidadController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function show($idEvento)
    {
        $publicidad = Publicidad::where("idEvento", $idEvento)->get();        
        return new ArchivoCollection($publicidad);
    }  
}
