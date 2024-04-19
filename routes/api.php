<?php

use App\Http\Controllers\EspacioController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\EventoController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\SolicitudEspacioController;
use App\Http\Controllers\AuthController;
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
});

Route::group(['/api'], function (){
    Route::apiResource('solicitud', SolicitudEspacioController::class);
    Route::apiResource('espacios', EspacioController::class);
    Route::apiResource('horarios', HorarioController::class);
});
