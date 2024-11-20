<?php

namespace App\Models;

use App\Models\Enums\TiposArchivosEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Archivo extends Model
{
    protected $table = 'archivos';

    protected $fillable = [
        "nombre",
        "ruta",
        "idEvento",
        "idTipoArchivo"
    ];

    public function evento(){
        return $this->belongsTo(Evento::class, 'idEvento', 'id');
    }
    public function tipoArchivo(){
        return $this->belongsTo(TipoArchivo::class, 'idTipoArchivo', 'id');
    }
    public static function bulkCreate(int $idEvento, TiposArchivosEnum $tipoArchivo, $archivos){

        foreach ($archivos as $archivo){
            if (!$archivo->isValid()) {
                return response()->json(['error' => 'El archivo ' . $archivo->getClientOriginalName() . 'no es válido.'], 400);
            }
        }

        foreach ($archivos as $archivo) {
            $path = $archivo->store("/", 'public');
            $archivoModel = new Archivo();
            $archivoModel->ruta = basename($path);
            $archivoModel->nombre = $archivo->getClientOriginalName();
            $archivoModel->idEvento = $idEvento;
            $archivoModel->idTipoArchivo = $tipoArchivo->value;
            $archivoModel->save();
        }
    }
}
