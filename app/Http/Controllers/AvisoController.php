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
        $noticesCollection = [];
        $user = User::findByToken($request);

        if (!$user){
            return response()->json(["message" => "Token inválido"], 401);
        }
        
        if ($user->idRol === RolEnum::coordinador->value){
            $noticesCollection = 
                Aviso::where("idEvento", "<>", null)
                    ->where("idTipoAviso", "=", TipoAvisoEventEnum::evento_nuevo)
                    ->orWhere("idTipoAviso", "=", TipoAvisoEventEnum::evento_evaluado)
                    ->with(["evento.reservaciones.espacio", "evento.programasEducativos"]);
        } 

        if ($user->idRol === RolEnum::administrador_espacios->value){
            $noticesCollection = 
                Aviso::where("idReservacion", "<>", null)
                    ->where("idTipoAviso", "=", TipoAvisoReservationEnum::reservacion_nueva)
                    ->with(["reservacion.usuario", 
                            "reservacion.espacio", 
                            "reservacion.estado"]);
        } 
        
        if ($user->idRol === RolEnum::organizador->value){
            $noticesCollection = 
                Aviso::where("idUsuario", "=", $user->id)
                    ->with(["evento", "reservacion.usuario", 
                            "reservacion.espacio", 
                            "reservacion.estado"]);
        } 
        
        $noticesCollection = $noticesCollection
            ->orderBy("visto")
            ->orderByDesc("updated_at");
        
        if ($request->query("soloCantidad")){
            return response()->json([
                "noticeAmount" => $this->countNoticeAmount(
                    $noticesCollection, 
                    $user->idRol),
                ]);
        }
            
        return new AvisoCollection($noticesCollection->paginate(5)->appends($request->query())); 
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
                TipoAvisoEventEnum::evento_rechazado->value,
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

            $notice = Aviso::findOrFail($request->input("id"));
            $originalRead = $notice->visto;

            $notice->update($nonNullData);

            $updated = $originalRead !== $notice->visto;
                
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
                'updated' => $updated
            ], $status);
        }
    } 
}
