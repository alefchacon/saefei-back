<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEvaluacionRequest extends FormRequest 
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() 
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() 
    {
        return [
			'calificacionAtencion' => 'required',
			'razonCalificacionAtencion' => ['required'],
			'calificacionComunicacion' => 'required',
			'mejorasApoyo' => 'required|string',
			'calificacionEspacio' => 'required',
			'problemasEspacio' => 'required|string',
			'calificacionCentroComputo' => 'required',
			'razonCalificacionCentroComputo' => 'required|string',
			'calificacionRecursos' => 'required',
			'razonCalificacionRecursos' => 'required|string',
			'problemaRecursos' => 'required|string',
			'mejorasRecursos' => 'required|string',
			'adicional' => 'required|string',
			'idEvento' => 'nullable|exists:eventos,idEvento|numeric',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
     
        ];
    }

}
