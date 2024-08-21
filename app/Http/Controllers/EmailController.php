<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArchivoResource;
use App\Http\Resources\EventoResource;
use App\Http\Resources\AvisoCollection;
use App\Models\Aviso;
use App\Models\Enums\EstadoEnum;
use App\Models\User;
use App\Models\Evento;
use App\Models\Evaluacion;
use App\Mail\MailProvider;
use App\Models\Enums\RolEnum;
use App\Mail\MailService;
use App\Utils\DateParser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */

    
    public function index()
    {
        $coordinator = User::where("idRol", RolEnum::coordinador)->first();
        //MailService::sendEvaluationPendingMail();
        
        $event = Evento::where("id", "=", 4)->with(["evaluacion", "usuario"])->first();
        MailProvider::SendEvaluationNewMail(event: $event);

        return response()->json($event);
    }  
}
