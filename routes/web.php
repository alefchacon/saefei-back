<?php

use App\Models\Evento;
use App\Models\Evidencia;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/', function () {
    return ['Laravel' => app()->version()];
});

//require __DIR__.'/auth.php';

Route::get('evento/{id}', function($idEvento){
    $pdf = new Pdf();
    $evento = Evento::with(['estado', 'tipo', 'modalidad', 'usuario' ])->find($idEvento);
    $pdf = Pdf::loadView('reporteActividadesFEI', $evento->toArray());
    $pdf->render();
    return $pdf->stream('reporteEvento.pdf'); 
});

Route::get('eventos/reporte', function (Request $request){
    $fechaInicio = $request->query('fechaInicio');
    $fechaFin = $request->query('fechaFin');
    $pdf = new Pdf();
    $eventos = Evento::whereBetween('inicio', [$fechaInicio, $fechaFin])->get();
    $data = [
        'eventos' => $eventos
    ];

    $pdf = Pdf::loadView('listaReporteActividadesFEI', $data);
    return $pdf->stream('reportesEventos.pdf');
});

Route::get('evidencia/{id}', function($idEvento){
    $pdf = new Pdf();
    $evento = Evento::with(['usuario' ])->find($idEvento);
    $data = [
        'evento' => $evento
    ];
    $pdf = Pdf::loadView('reporteEvidenciasFEI', $data);
    Pdf::setOption('defaultFont', 'sans-serif');
    $pdf->render();
    return $pdf->stream('reporteEvento.pdf');
});

Route::get('evidencias/reporte', function (Request $request){
    $fechaInicio = $request->query('fechaInicio');
    $fechaFin = $request->query('fechaFin');
    $pdf = new Pdf();
    $eventos = Evento::whereBetween('inicio', [$fechaInicio, $fechaFin])->get();

    $data = [
        'eventos' => $eventos
    ];

    $pdf = Pdf::loadView('listaReporteEvidenciasFEI', $data);
    return $pdf->stream('reportesEventos.pdf');
});

