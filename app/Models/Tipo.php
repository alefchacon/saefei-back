<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre'
    ];

    public function event(){
        return $this->hasMany(Evento::class, 'idTipo', 'id');
    }
}
