<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidencia extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "tipo",
        "idEvaluacion",
        "archivo",
    ];

    public function evaluacion() {
        return $this->belongsTo( Evaluacion::class, 'idEvaluacion', 'id');
    }
}