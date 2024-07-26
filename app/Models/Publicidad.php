<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publicidad extends Model
{
    use HasFactory;
    protected $table = 'publicidades';

    protected $fillable = [
        'archivo',
        "nombre",
        "tipo",
        'idEvento'
    ];

    public function getArchivoAttribute($value)
    {
        return base64_encode($this->attributes['archivo']);
    }

    public function evento() {
        return $this->belongsTo( Evento::class, 'idEvento', 'id');
    }
}
