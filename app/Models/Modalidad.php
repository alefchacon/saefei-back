<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $fillable = [
        'nombre'
    ];

    public function evento(){
        $this->belongsTo(Evento::class, 'idEvento');
    }
    use HasFactory;
}
