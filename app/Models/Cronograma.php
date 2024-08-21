<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{

    protected $table = "cronogramas";
    

    protected $fillable = [
        "nombre",
        "tipo",
        "idEvento",
        "archivo",
    ];

    public function getArchivoAttribute($value)
    {
        return base64_encode($this->attributes['archivo']);
    }

    public function evento() {
        return $this->belongsTo( Evento::class, 'idEvento', 'id');
    }
}
