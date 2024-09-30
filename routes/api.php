<?php

use App\Http\Controllers\CronogramaController;
use App\Http\Controllers\DifusionController;
use App\Http\Controllers\EspacioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\InvitadoController;
use App\Http\Controllers\ModalidadController;
use App\Http\Controllers\PlataformaController;
use App\Http\Controllers\ProgramaEducativoController;
use App\Http\Controllers\PublicidadController;
use App\Http\Controllers\ReservacionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TipoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\AvisoController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ArchivoController;
use App\Http\Middleware\AuthCustom;
use App\Models\Difusion;
use App\Models\Espacio;
use App\Models\Estado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
*/


Route::group(['/api'], function () {
    Route::apiResource('evaluaciones', EvaluacionController::class);
    Route::post('eventos/mes', [EventoController::class, 'getByMonth']);
    Route::post('eventos/nombre', [EventoController::class, 'getEventosPorNombre']);
    Route::apiResource('evidencias', EvidenciaController::class);
    Route::post('evidencias', [EvidenciaController::class, "getEvidencesFor"]);
    Route::apiResource('estados', EstadoController::class);
    Route::get('eventos/{id}', [EventoController::class, "show"]);
    
    Route::apiResource('usuarios', UserController::class);
    Route::put('usuarios', [UserController::class, "update"]);

    Route::get('perfil', [UserController::class, "showByToken"]);
    
    Route::apiResource('roles', RolController::class);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    
    Route::apiResource('difusiones', DifusionController::class);
    Route::apiResource('invitados', InvitadoController::class);
    Route::apiResource('modalidades', ModalidadController::class);
    Route::apiResource('plataformas', PlataformaController::class);
    Route::apiResource('programaseducativos', ProgramaEducativoController::class);
    Route::apiResource('publicidad', PublicidadController::class);
    Route::apiResource('tipos', TipoController::class);
    
    Route::apiResource('espacios', EspacioController::class);
    Route::post('espacios/reservaciones', [EspacioController::class, 'getEspaciosLibres']);
    Route::put('espacios/reservaciones', [EspacioController::class, 'getEspaciosLibres']);
    
    Route::apiResource('reservaciones', ReservacionController::class);

    Route::post('reservaciones/disponibles', [ReservacionController::class, 'getAvailableReservations']);
    Route::post('solicitud/marcarLeidasUsuario', [ReservacionController::class, 'markAsUserRead']);
    //Route::put('solicitud', [SolicitudEspacioController::class, 'update']);
    Route::apiResource('horarios', HorarioController::class);

    Route::apiResource('eventos', EventoController::class);
    Route::apiResource('avisos', AvisoController::class)->middleware(AuthCustom::class);
    Route::put('avisos', [AvisoController::class, "update"])->middleware(AuthCustom::class);
    //Route::post('avisos/marcarLeidasUsuario', [AvisoController::class, "markAsUserRead"]);

    Route::apiResource('cronogramas', CronogramaController::class);
    Route::apiResource('publicidad', PublicidadController::class);
    Route::apiResource('email', EmailController::class)->middleware(AuthCustom::class);


    Route::post('/archivos', [ArchivoController::class, 'upload']);
    Route::get('/file/{filename}', [ArchivoController::class, 'download']);


});

    
    
Route::group(['/api', 'middleware' => 'auth:sanctum'], function (){
        
});
