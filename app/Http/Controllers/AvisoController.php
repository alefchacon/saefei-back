<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvisoCollection;
use App\Models\Aviso;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
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
        $user = User::findByToken($request);

        if (!$user){
            return response()->json(["message" => "Token inválido"], 401);
        }

        $amountOnly = $request->query("soloCantidad");

        $response = [];

        $response["noticesEvent"] = 
            new AvisoCollection(Aviso::getEventNoticesFor(idUsuario: $user->id)->with("evento")->get());
        $response["noticesReservation"] = 
            new AvisoCollection(Aviso::getReservationNoticesFor(idUsuario: $user->id)->get());
        if ($user->isCoordinator()){
            $response["noticesCoordinator"] = 
                new AvisoCollection(Aviso::getCoordinatorNotices()->with("evento")->get());
        }
        if ($user->isAdministrator()){
            $response["noticesAdministrator"] = 
                new AvisoCollection(Aviso::getAdministratorNoticesFor(idUsuario: $user->id)->with(["reservacion", "reservacion.espacio", "reservacion.usuario"])->get());
        }

        if ($amountOnly==="true"){
            return response()->json([
                "noticeAmount" => 
                    count($response["noticesEvent"]) 
                    + count($response["noticesReservation"]),
                "noticeAmountStaff" => 
                    ( isset($response["noticesCoordinator"]) ? count($response["noticesCoordinator"]) : 0)
                    +( isset($response["noticesAdministrator"]) ? count($response["noticesAdministrator"]) : 0)
        
        ]);
        }
        
        return response()->json(["data" => $response], 200);
    }    

    
    private function countNoticeAmount(Builder $notices, int $idRol){
        $tipoAvisosToNotify = [];

        if ($idRol === RolEnum::coordinador->value){
            $tipoAvisosToNotify = [
                TipoAvisoEventEnum::evento_nuevo->value,
                TipoAvisoEventEnum::evento_evaluado->value
            ];
        } 

        if ($idRol === RolEnum::administrador_espacios->value){
            $tipoAvisosToNotify = [
                TipoAvisoReservationEnum::reservacion_nueva->value,
            ];
        } 
        
        if ($idRol === RolEnum::organizador->value){
            $tipoAvisosToNotify = [
                TipoAvisoEventEnum::evento_aceptado->value,
                TipoAvisoReservationEnum::reservacion_aceptada->value,
                TipoAvisoReservationEnum::reservacion_rechazada->value
            ];
        } 

        return $notices->get()->filter(function ($notice) use ($tipoAvisosToNotify) {
            return ($notice["visto"] === 0 && in_array($notice["idTipoAviso"], $tipoAvisosToNotify)); 
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

            $originalRead = $aviso->visto;

            $aviso->update($nonNullData);

            $updated = $originalRead !== $aviso->visto;
                
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
                'updated' => $aviso
            ], $status);
        }
    } 
}
