<?php

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
use App\Http\Controllers\SolicitudEspacioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TipoController;
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
    Route::apiResource('eventos', EventoController::class);
    Route::apiResource('estados', EstadoController::class);

    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

    Route::apiResource('difusiones', DifusionController::class);
    Route::apiResource('invitados', InvitadoController::class);
    Route::apiResource('modalidades', ModalidadController::class);
    Route::apiResource('plataformas', PlataformaController::class);
    Route::apiResource('programasEducativos', ProgramaEducativoController::class);
    Route::apiResource('publicidad', PublicidadController::class);
    Route::apiResource('tipos', TipoController::class);

});

Route::group(['/api'], function (){
    Route::apiResource('solicitud', SolicitudEspacioController::class);
    Route::apiResource('espacios', EspacioController::class);
    Route::apiResource('horarios', HorarioController::class);
});
