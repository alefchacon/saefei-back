<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aviso extends Model
{
    use HasFactory;
    

    protected $fillable = [
        "idUsuario",
        "avisarUsuario",
        "avisarStaff",
        "idEvento",
        "idSolicitudEspacio"
    ];

    public function solicitudEspacio(){
        return $this->belongsTo(SolicitudEspacio::class, 'idSolicitudEspacio', 'id');
    }
    public function evento(){
        return $this->belongsTo(Evento::class, 'idEvento', 'id');
    }

}
