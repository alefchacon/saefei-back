<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use App\Http\Resources\AvisoCollection;
use App\Http\Resources\RespuestaCollection;
use App\Http\Resources\RespuestaResource;
use App\Models\Aviso;
use App\Models\Cronograma;
use App\Models\Eventos_ProgramaEducativos;
use App\Models\Difusion;
use App\Models\Publicidad;
use App\Models\Respuesta;
use App\Models\User;
use App\Models\Reservacion;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Modalidade;
use App\Models\Estado;
use App\Models\Tipo;
use App\Filters\EventoFilter;
use App\Http\Resources\EventoCollection;
use Exception;
use App\Mail\Mailer;
use App\Mail\Mail;
use App\Mail\MailFactory;
use App\Models\Enums\EstadoEnum;
use App\Models\Enums\RolEnum;

class AvisoController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function index(Request $request)
    {
        $noticesCollection = [];
        $user = User::findByToken($request);
        
        if ($user->idRol === RolEnum::coordinador->value){
            $noticesCollection = Aviso::where("idEvento", "<>", null)->with("evento")->orderBy("visto");
        } 

        if ($user->idRol === RolEnum::administrador_espacios->value){
            $noticesCollection = 
                Aviso::where("idReservacion", "<>", null)
                    ->with(["reservacion.usuario", 
                            "reservacion.espacio", 
                            "reservacion.estado"])
                    ->orderBy("visto");
        } 
        
                    
        
        if ($user->idRol === RolEnum::organizador->value){
            $noticesCollection = 
                Aviso::where("idUsuario", "=", $user->id)
                    ->with(["evento", "reservacion.usuario", 
                            "reservacion.espacio", 
                            "reservacion.estado"])
                    ->orderBy("visto");
        } 
        
        $noticeAmount = $this->countNoticeAmount($noticesCollection, "vistoOrganizador");
        $noticesCollection = new AvisoCollection($noticesCollection->paginate(50)->appends($request->query()));
        
        return response()->json([
            "noticeAmount" => $noticeAmount,
            "data" => $noticesCollection->resource,
        ]);
        
    }    

    
    private function countNoticeAmount(Builder $notices, string $type = "vistoStaff"){
        
        return $notices->get()->filter(function ($notice) use ($type) {
            return (($notice["visto"] === 0)); 
        })->count();
    }



    public function update(Request $request,Aviso $aviso)
    {
        $status = 500;
        $message = "Algo falló";

        
        \DB::beginTransaction();
        try{
            $nonNullData = array_filter($request->all(), function ($value) {
                return !is_null($value);
            });

            Aviso::findOrFail($request->input("id"))
                ->update($nonNullData);
                
            \DB::commit();
            
            $message = "Aviso actualizado";
            $status = 200;
        } catch (\Exception $ex){

            \DB::rollBack();

            $message = $ex->getMessage();
        }finally {
            //MailFactory::sendEventReplyMail($event);
            return response()->json([
                'message' => $message,
            ], $status);
        }
    } 
}
