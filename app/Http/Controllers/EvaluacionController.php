<?php

namespace App\Http\Controllers;

use App\Http\Resources\EvaluacionResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evaluacion;
use App\Models\Evento;
use App\Resources\EvaluacionCollection;


/**
 * Description of EvaluacionController
 *
 * @author Alejandro Chacón
 */

class EvaluacionController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function index(Request $request)	
    {
        return Evaluacion::all();
    }    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Evaluacion $evaluacion)
    {
        return view('pages.evaluaciones.show', [
                'record' =>$evaluacion,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     */
    public function create(Request $request)
    {
        return new EvaluacionResource(Evaluacion::create($request->all()));
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $model=new Evaluacion;

        error_log($request);
        $model->fill($request->all());
        if ($model->save()) {
            
            session()->flash('app_message', 'Ev aluacion saved successfully');
            return redirect()->route('evaluaciones.index');
            } else {
                session()->flash('app_message', 'Something is wrong while saving Evaluacion');
            }
        return redirect()->back();
    } /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Evaluacion $evaluacion)
    {
		$eventos = Evento::all(['id']);

        return view('pages.evaluaciones.edit', [
            'model' => $evaluacion,
			"eventos" => $eventos,

            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Evaluacion $evaluacion)
    {
        $evaluacion->fill($request->all());

        if ($evaluacion->save()) {
            
            session()->flash('app_message', 'Evaluacion successfully updated');
            return redirect()->route('evaluaciones.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating Evaluacion');
            }
        return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Request  $request
     * @param  Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Evaluacion $evaluacion)
    {
        if ($evaluacion->delete()) {
                session()->flash('app_message', 'Evaluacion successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Evaluacion');
            }

        return redirect()->back();
    }
}
