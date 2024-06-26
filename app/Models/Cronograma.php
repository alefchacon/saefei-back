<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "tipo",
        "idEvento",
        "archivo",
    ];

    public function evento() {
        return $this->belongsTo( Evento::class, 'idEvento', 'id');
    }
}
