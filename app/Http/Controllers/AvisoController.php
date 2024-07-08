<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventoResource;
use App\Http\Resources\AvisoCollection;
use App\Models\Aviso;
use App\Models\Cronograma;
use App\Models\Eventos_ProgramaEducativos;
use App\Models\Difusion;
use App\Models\Publicidad;
use App\Models\User;
use App\Models\SolicitudEspacio;
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

        
        $forUser = $request->query("idUsuario");
        $view = $request->query("vista");
        $amountOnly = $request->query("cantidad");
        

        $noticesCollection = [];
        if ($view === "coord"){
            $noticesCollection = Aviso::where("idEvento", "<>", null)->orderByDesc("avisarStaff");
        }
        if ($view === "admin"){
            $noticesCollection = Aviso::where("idSolicitudEspacio", "<>", null)->orderByDesc("avisarStaff");;
        }
        if ($view === "todos"){
            $noticesCollection = Aviso::orderByDesc("avisarStaff");;
        }
        else if ($forUser){
            $noticesCollection = Aviso::where("idUsuario", "=", $forUser)->orderByDesc("avisarUsuario");;
        }
        $noticesCollection->with(["evento.solicitudesEspacios.espacio", "solicitudEspacio.espacio", "solicitudEspacio.estado"]);
        
        $noticeAmount = $noticesCollection->get()->filter(function ($notice) {
            return (($notice->avisarUsuario > 0) || ($notice->avisarStaff > 0)); 
        })->count();


        $noticesCollection = new AvisoCollection($noticesCollection->paginate(5)->appends($request->query()));
        

        return response()->json([
            "data" => $noticesCollection->resource,
            "noticeAmount" => $noticeAmount,
        ]);
        
    }    

    

    
    
    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Evento $evento)
    {
        $status = 500;
        $message = "Algo falló";
        try{
            $model = Evento::findOrFail($evento->id);
            
            $modelIdEstado = $model->idEstado;
            $model->update($request->all());
            
            $requestIdEstado = $request->input("idEstado");

            if ($modelIdEstado != $requestIdEstado){
                $this->changeEventStatus($model, EstadoEnum::tryFrom($requestIdEstado));
            } 

            
            \DB::commit();
            
            
            $message = "Evento actualizado";
            
            
            $status = 200;
        } catch (\Throwable $ex){
            \DB::rollBack();
            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => new EventoResource($model),
            ], $status);
        }
    } 

    public function markAsUserRead(Request $request)
    {
        $status = 500;
        $message = "Algo falló";

        $notices = $request->input("notices");
        $updatedNotices = [];
        \DB::beginTransaction();

        try{

            $noticesUpdated = 0;
            foreach ($notices as $notice) {
                $model = Aviso::findOrFail($notice["id"]);

                if ($model->avisarUsuario !== 0){
                    $model->update(["avisarUsuario" => 0]);
                    $noticesUpdated++;
                }
                
                array_push($updatedNotices, $model);
            } 
            
            \DB::commit();
            
            $message = "Reservaciones actualizadas";
            $status = 200;
        } catch (\Exception $ex){

            \DB::rollBack();

            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => $updatedNotices,
                'noticesUpdated' => $noticesUpdated
            ], $status);
        }
    }

}
