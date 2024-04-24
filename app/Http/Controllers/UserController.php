<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Filters\UsuarioFilter;
use App\Models\User;
use App\Http\Resources\UserCollection;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = new UsuarioFilter();
        $queryItems = $filter->transform($request);
        
        $includeRol = $request->query("rol");

        $eventos = User::where($queryItems);
        
        
        if ($includeRol) {
            $eventos = $eventos->with("rol");
        }
        

        return new UserCollection($eventos->paginate()->appends($request->query())); 
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
        $model=new User;

        $model->fill($request->all());

        $message = "Algo falló";
        $code = 500;
        try{
            $model->save();
            $model->load("rol");
            $message = 'Usuario registrado';
            $code = 201;
        }catch (QueryException $ex) {

            //cambiar esto por un diccionario de errores: 
            $errorCode = $ex->errorInfo[1];
            switch ($errorCode){
                case 1062: 
                    $message = 'El email ya fue registrado';
                    $code = 409;
                    break;
            }
        }finally{
            return response()->json([
                'message' => $message,
                'data'=> new UserResource($model)], $code);
        } 
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
