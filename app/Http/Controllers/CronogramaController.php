<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArchivoCollection;
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
    public function show($idEvento)
    {
        
        $cronograma = Cronograma::where("idEvento",$idEvento)->get();
        
        return new ArchivoCollection($cronograma);
    }  
}
