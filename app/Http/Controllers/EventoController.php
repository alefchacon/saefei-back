<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Usuario;
use App\Models\Modalidade;
use App\Models\Estado;
use App\Models\Tipo;
use App\Filters\EventoFilter;
use App\Http\Resources\EventoCollection;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;

/**
 * Description of EventoController
 *
 * @author Tuhin Bepari <digitaldreams40@gmail.com>
 */
use function Pest\Laravel\json;

class EventoController extends Controller
{
       /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     */
    public function index(Request $request)
    {
        $filter = new EventoFilter();
        $queryItems = $filter->transform($request);
        
        $includeEvaluacion = $request->query("evaluacion");
        $includeEstado = $request->query("estado");

        $eventos = Evento::where($queryItems);
        
        
        if ($includeEvaluacion) {
            $eventos = $eventos->with("evaluacion");
        }
        if ($includeEstado) {
            $eventos = $eventos->with("estado");
        }
        

        return new EventoCollection($eventos->paginate()->appends($request->query())); 
    }    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Evento $evento)
    {
        return view('pages.eventos.show', [
                'record' =>$evento,
        ]);

    }    /**
     * Show the form for creating a new resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$usuario = Usuario::all(['id']);
		$modalidades = Modalidade::all(['id']);
		$estados = Estado::all(['id']);
		$tipos = Tipo::all(['id']);

        return view('pages.eventos.create', [
            'model' => new Evento,
			"usuario" => $usuario,
			"modalidades" => $modalidades,
			"estados" => $estados,
			"tipos" => $tipos,

        ]);
    }    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $model = new Evento;
            $model->nombreOrganizador = $request->nombreOrganizador;
            $model->puesto = $request->puesto;
            $model->email = $request->email;
            $model->nombre = $request->nombre;
            $model->descripcion = $request->descripcion;
            $model->numParticipantes = $request->numParticipantes;
            $model->requisitosCentroComputo = $request->requisitosCentroComputo;
            $model->numParticipantesExternos = $request->numParticipantesExternos;
            $model->requiereEstacionamiento = $request->requiereEstacionamiento;
            $model->requiereFinDeSemana = $request->requiereFinDeSemana;
            $model->requiereMaestroDeObra = $request->requiereMaestroDeObra;
            $model->requiereNotificarPrensaUV = $request->requiereNotificarPrensaUV;
            $model->adicional = $request->adicional;
            $model->inicio = $request->inicio;
            $model->fin = $request->fin;
            $model->idUsuario = $request->idUsuario;
            $model->idModalidad = $request->idModalidad;
            $model->idEstado = $request->idEstado;
            $model->idTipo = $request->idTipo;
            $model->idPrograma = $request->idPrograma;
            $model->idPlataforma = $request->idPlataforma;
            // $model->idEspacio = $request->idEspcacio;

            try {
                $file = $request->file('cronograma');
                if ($file->isValid()) {
                    $blob = file_get_contents($file->getRealPath());
                    $model->cronograma = $blob;
                }
                $message = 'Evento registrado. ';
                // $model->save();
            } catch (Exception $ex) {
                $message = "Error";
                return response()->json([
                    'message' => $ex
                ]);
            }

            if ($model->save()) {
                return response()->json([
                    'message' => $message,
                    'evento' => $model->id,
                ], 200);
            }
        } catch (Exception $ex) {
            $message = "Error";
            return response()->json([
                'message' => $ex
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Evento $evento)
    {
		$usuario = Usuario::all(['id']);
		$modalidades = Modalidade::all(['id']);
		$estados = Estado::all(['id']);
		$tipos = Tipo::all(['id']);

        return view('pages.eventos.edit', [
            'model' => $evento,
			"usuario" => $usuario,
			"modalidades" => $modalidades,
			"estados" => $estados,
			"tipos" => $tipos
            ]);
    }    /**
     * Update a existing resource in storage.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Evento $evento)
    {
        $evento->fill($request->all());

        if ($evento->save()) {
            
            session()->flash('app_message', 'Evento successfully updated');
            return redirect()->route('eventos.index');
            } else {
                session()->flash('app_error', 'Something is wrong while updating Evento');
            }
        return redirect()->back();
    }    /**
     * Delete a  resource from  storage.
     *
     * @param  Request  $request
     * @param  Evento  $evento
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Request $request, Evento $evento)
    {
        if ($evento->delete()) {
                session()->flash('app_message', 'Evento successfully deleted');
            } else {
                session()->flash('app_error', 'Error occurred while deleting Evento');
            }

        return redirect()->back();
    }
}
