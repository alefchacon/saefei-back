<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;
    

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado"
    ];

    public function solicitudesEspacios(){
        return $this->hasMany(SolicitudEspacio::class, 'idEspacio', 'id');
    }

    public static function encontrarPor($inicio, $fin,){
        $reservados = self::whereHas('solicitudesEspacios', function ($query) use ($inicio, $fin) {
            $query->where('inicio', '<=', $inicio)
                  ->where('fin', '>=', $fin)
                  ->orWhere('inicio', '<=', $inicio)
                  ->where('fin', '>=', $inicio)
                  ->orWhere('inicio', '>=', $inicio)
                  ->where('inicio', '<=', $fin);
        })
        ->get()
        ->load("solicitudesEspacios");
        $idReservados = $reservados->pluck("id");
        $disponibles = self::whereNotIn('id', $idReservados)->get();
        $resultado = $reservados->merge($disponibles);
        return $resultado;
    }
}
