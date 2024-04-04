<?php

use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\EventoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['/api'], function () {
    Route::apiResource('evaluaciones', EvaluacionController::class);
    Route::apiResource('eventos', EventoController::class);
});
