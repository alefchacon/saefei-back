<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvisoCollection;
use App\Models\Archivo;
use App\Models\Aviso;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;
use App\Models\Enums\RolEnum;
use Storage;
use App\Models\Enums\TiposArchivosEnum;

class ArchivoController extends Controller
{
    public function upload(Request $request)
    {

        $uploader = User::findByToken($request);
        $evento = Evento::find($request->input("idEvento"));

        if (!$uploader){
            return response()->json(["message" => "Token inválido"], 401);
        }

        $canEdit = 
            $uploader->isCoordinator() 
            || $uploader->id === $evento->idUsuario;
    
        if (!$canEdit){
            return response()->json(["message" => "No tiene permiso para realizar esta operación"], 403);
        }

        $idTipoArchivo = $request->input("idTipoArchivo");
        if (!TiposArchivosEnum::tryFrom($idTipoArchivo)){
            return response()->json(['message' =>"El tipo de archivo seleccionado no existe" ], 400);
        }

        $archivos = $request->file("archivo");

        Archivo::bulkCreate(
            $evento->id, 
            TiposArchivosEnum::from($idTipoArchivo), 
            $archivos
        );

        return response()->json(['message' => "Archivo subido" ], 201);
    }


    public function download($filename)
    {
        // Get the file from storage
        return Storage::disk('public')->download('/' . $filename);
    }
}
