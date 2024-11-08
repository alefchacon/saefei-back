<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservacionCollection;
use App\Http\Resources\ReservacionResource;
use App\Mail\MailProvider;
use App\Mail\MailService;
use App\Models\Enums\RolEnum;
use App\Models\Enums\TipoAvisoEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
use App\Models\Espacio;
use App\Models\User;
use App\Models\Reservacion;
use App\Models\Aviso;
use Illuminate\Http\Request;
use App\Filters\ReservacionFilter;
use App\Models\Enums\EstadoEnum;


class ReservacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new ReservacionFilter();
        $queryItems = $filter->transform($request);
        $orderByCoordinatorNotice = $request->query("porAvisosAdministrador");
        $orderByUserNotice = $request->query("porAvisosUsuario");

        $solicitudes = Reservacion::where($queryItems)->with([ 
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

        return new ReservacionCollection($solicitudes->get());
    }
    public function getAvailableReservations(Request $request)
    {
        $idUsuario = $request->input("idUsuario");

        $solicitudes = Reservacion
            ::where("idUsuario", "=", $idUsuario)
            ->where("idEstado", "=", 2)
            ->with([
                "usuario", 
                "estado", 
                "espacio", 
            ])->get();

        return new ReservacionCollection($solicitudes);
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

        $reservation = new Reservacion();
        try {

            $reservation->fill($request->all());
            $reservation->save();
            
            Aviso::create([
                "visto" => 0,
                "idUsuario" => null,
                "idReservacion" => $reservation->id,
                "idEstado" => EstadoEnum::en_revision,
                "idTipoAviso" => TipoAvisoReservationEnum::reservacion_nueva
            ]);

            $reservation->with([
                "usuario", 
                "estado", 
                "espacio", 
            ])->get();
            
            $mail = MailProvider::getReservationMail(
                $reservation, 
                TipoAvisoReservationEnum::reservacion_nueva
            );
            $admins = User::where("idRol", RolEnum::administrador_espacios)->get();
            foreach($admins as $admin){
                MailService::sendEmail(
                    to: $admin, 
                    mail: $mail
                );
            }

            $message = 'Solicitud enviada';
            $status = 201;
            $data = new ReservacionResource($reservation);

        } catch (\Throwable $ex){
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservacion $reservation)
    {
        $status = 500;
        $message = "Algo falló";

        \DB::beginTransaction();

        try{
            $nonNullData = array_filter($request->input("model"), function ($value) {
                return !is_null($value);
            });
            $reservation = Reservacion::findOrFail($nonNullData["id"]);
            $originalIdEstado = $reservation->idEstado;
            $reservation->update($nonNullData);

            
            Aviso::notifyResponse(
                $request->input("idAviso"), 
                $reservation, 
                $originalIdEstado
            );

            self::handleReservationMail($reservation, $originalIdEstado);

            \DB::commit();
            
            $message = "Reservación actualizada";
            $status = 200;
        } catch (\Throwable $ex){

            \DB::rollBack();

            $message = $ex->getMessage();
        }finally {
            return response()->json([
                'message' => $message,
                'data' => $reservation,
            ], $status);
        }
    }

    private static function handleReservationMail(
        Reservacion $reservation, 
        int $originalIdEstado
    ){
        $replyingToEventOrganizer = 
            $originalIdEstado !== $reservation->idEstado;

        if (!$replyingToEventOrganizer){
            return;
        }

        $type = TipoAvisoReservationEnum::mapFrom($reservation->idEstado);
        
        $mail = MailProvider::getReservationMail(
            $reservation, 
            $type
        );

        $reservation->load('usuario');
        MailService::sendEmail(
            to: $reservation->usuario,
            mail: $mail 
        );
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
                $model = Reservacion::findOrFail($reservation["id"]);
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

}
