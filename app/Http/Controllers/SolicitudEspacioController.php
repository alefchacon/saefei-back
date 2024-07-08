<?php

namespace App\Http\Controllers;

use App\Http\Resources\SolicitudEspacioCollection;
use App\Http\Resources\SolicitudEspacioResource;
use App\Models\Espacio;
use App\Models\User;
use App\Models\SolicitudEspacio;
use App\Models\Aviso;
use Illuminate\Http\Request;
use App\Filters\SolicitudEspacioFilter;
use App\Models\Enums\EstadoEnum;


class SolicitudEspacioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new SolicitudEspacioFilter();
        $queryItems = $filter->transform($request);
        $orderByCoordinatorNotice = $request->query("porAvisosAdministrador");
        $orderByUserNotice = $request->query("porAvisosUsuario");

        $solicitudes = SolicitudEspacio::where($queryItems)->with([ 
            "usuario", 
            "estado", 
            "espacio",
        ]);

        if ($orderByCoordinatorNotice) {
            $solicitudes = $solicitudes->orderByDesc("avisarAdministrador");
        }
        if ($orderByUserNotice) {
            $solicitudes = $solicitudes->orderByDesc("avisarUsuario");
        }

        return new SolicitudEspacioCollection($solicitudes->paginate(5)->appends($request->query()));
    }
    public function getAvailableReservations(Request $request)
    {
        $idUsuario = $request->input("idUsuario");

        $solicitudes = SolicitudEspacio
            ::where("idUsuario", "=", $idUsuario)
            ->where("idEstado", "=", 2)
            ->with([
                "usuario", 
                "estado", 
                "espacio", 
            ])->get();

        return new SolicitudEspacioCollection($solicitudes);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $message = "Algo falló.";
        $status = 500;
        $data = "";

        $solicitud = new SolicitudEspacio();
        try {

            $solicitud->fill($request->all());
            $solicitud->avisarAdministrador = 1;
            $solicitud->save();
            
            Aviso::create([
                "avisarStaff" => 1,
                "idUsuario" => $solicitud->idUsuario,
                "idSolicitudEspacio" => $solicitud->id
            ]);

            $solicitud->with([
                "usuario", 
                "estado", 
                "espacio", 
            ])->get();
            
            $message = 'Solicitud realizada. Espere confirmacion';
            $status = 201;
            $data = new SolicitudEspacioResource($solicitud);

        } catch (\Exception $ex){
            //No regresar excepciones: cambiar a mensaje personalizado y ambiguo.
            $message = $ex->getMessage();
        } finally {
            
            return response()->json([
                'message' => $message,
                'data' => $data,
            ], $status);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SolicitudEspacio $solicitudEspacio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SolicitudEspacio $solicitudEspacio)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SolicitudEspacio $solicitud)
    {
        $status = 500;
        $message = "Algo falló";

        $isReply = $request->query("respuesta");
        $markUserRead = $request->query("vistoUsuario");
        $markAdminRead = $request->query("vistoAdministrador");

        \DB::beginTransaction();

        try{

            $solicitud->update($request->all());

            if ($isReply) {
                    
                $estado = EstadoEnum::tryFrom(
                    $request->input("idEstado")
                );

                //$this->changeEventStatus($evento, $estado);
                 
                $solicitud->update(["avisarUsuario" => 1]);
                $solicitud->update(["avisarAdministrador" => 0]);
            }
            
            if ($markUserRead) {
                $solicitud->update(["avisarUsuario" => 0]);
            }
            if ($markAdminRead) {
                $solicitud->update(["avisarCoordinador" => 0]);
            }
            
            \DB::commit();
            
            $message = "Reservación actualizada";
            $status = 200;
        } catch (\Exception $ex){

            \DB::rollBack();

            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => $solicitud,
            ], $status);
        }
    }

    public function markAsUserRead(Request $request)
    {
        $status = 500;
        $message = "Algo falló";

        $reservations = $request->input("reservations");
        $updatedReservations = [];
        \DB::beginTransaction();

        try{

            foreach ($reservations as $reservation) {
                $model = SolicitudEspacio::findOrFail($reservation["id"]);
                $model->update(["avisarUsuario" => 0]);
                
                array_push($updatedReservations, $model);
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
                'data' => $updatedReservations,
            ], $status);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SolicitudEspacio $solicitudEspacio)
    {
        //
    }
}
