<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
