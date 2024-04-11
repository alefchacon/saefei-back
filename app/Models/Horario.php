<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = [
        'fecha',
        "inicio",
        "fin"
    ];
    
    
    public function solicituEspacio(){
        return $this->belongsTo(SolicitudEspacio::class, 'idhorario');
    }
    use HasFactory;
}
