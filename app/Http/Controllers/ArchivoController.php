<?php

namespace App\Http\Controllers;

use App\Http\Resources\AvisoCollection;
use App\Models\Aviso;
use App\Models\Enums\TipoAvisoEventEnum;
use App\Models\Enums\TipoAvisoReservationEnum;
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
        $request->validate([
            'file' => 'required|file|mimes:jpg,png,pdf,docx|max:2048',
        ]);

        $path = $request->file('file')->store('uploads', 'public');

        $idTipoArchivo = $request->input('idTipoArchivo');
        if (!TiposArchivosEnum::tryFrom($idTipoArchivo)){
            return response()->json(['message' =>"El tipo de archivo seleccionado no existe" ], 401);
        }

        return response()->json(['path' =>TiposArchivosEnum::tryFrom($idTipoArchivo) ], 201);
    }


    public function download($filename)
    {
        // Get the file from storage
        return Storage::disk('public')->download('uploads/' . $filename);
    }
}
