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
        $noticeAmount = 0;
        if ($view === "coord"){
            $noticesCollection = Aviso::where("idEvento", "<>", null)->orderByDesc("avisarStaff");
            $noticeAmount = $this->countNoticeAmount($noticesCollection, "avisarStaff");
        }
        if ($view === "admin"){
            $noticesCollection = Aviso::where("idSolicitudEspacio", "<>", null)->orderByDesc("avisarStaff");;
            $noticeAmount = $this->countNoticeAmount($noticesCollection, "avisarStaff");
        }
        if ($view === "todos"){
            $noticesCollection = Aviso::orderByDesc("avisarStaff");;
        }
        else if ($forUser){
            $noticesCollection = Aviso::where("idUsuario", "=", $forUser)->orderByDesc("avisarUsuario");;
            $noticeAmount = $this->countNoticeAmount($noticesCollection, "avisarUsuario");
        }
        $noticesCollection->with(["evento.solicitudesEspacios.espacio", "solicitudEspacio.espacio", "solicitudEspacio.estado", "solicitudEspacio.usuario"]);
        


        $noticesCollection = new AvisoCollection($noticesCollection->paginate(5)->appends($request->query()));
        

        return response()->json([
            "data" => $noticesCollection->resource,
            "noticeAmount" => $noticeAmount,
        ]);
        
    }    


    private function countNoticeAmount(Builder $notices, string $type = "avisarStaff"){

        return $notices->get()->filter(function ($notice) use ($type) {
            return (($notice[$type] > 0)); 
        })->count();
    }

    public function markAsUserRead(Request $request)
    {
        $status = 500;
        $message = "Algo falló";

        $notices = $request->input("notices");
        $type = $request->query("tipo");

        $updatedNotices = [];
        \DB::beginTransaction();

        try{

            $noticesUpdated = 0;
            foreach ($notices as $notice) {
                $model = Aviso::findOrFail($notice["id"]);

                if ($type === "staff"){
                    $model->update(["avisarStaff" => 0]);
                } else {
                    $model->update(["avisarUsuario" => 0]);
                }
                $noticesUpdated++;
                
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
