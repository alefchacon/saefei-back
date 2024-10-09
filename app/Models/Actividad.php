<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;
    protected $table = 'actividades';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'hora',
        'idReservacion'
    ];
    
    public function reservacion(){
        return $this->belongsTo(Reservacion::class, 'idReservacion', 'id');
    }

}
