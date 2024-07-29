<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArchivoResource;
use App\Http\Resources\EventoResource;
use App\Http\Resources\AvisoCollection;
use App\Models\Aviso;
use App\Models\User;
use App\Models\Evento;
use App\Mail\MailFactory;
use App\Models\Enums\RolEnum;
use App\Mail\Mailer;
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
        $event = Evento::findOrFail(1);
        /*
        Mailer::sendEmail(to: $coordinator, mail: MailFactory::GetEventNewMail());*/


        $start="1972-05-02";
        $end="1972-05-02";


        return response()->json(DateParser::translateDateString($start, $end));
    }  
}
