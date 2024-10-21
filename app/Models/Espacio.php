<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    use HasFactory;
    

    protected $fillable = [
        "nombre",
        "capacidad",
        "idAdministrador"
    ];

    public function reservaciones(){
        return $this->hasMany(Reservacion::class, 'idEspacio', 'id');
    }

    public static function encontrarPorHorario($inicio, $fin,){
        $reservados = self::whereHas('reservaciones', function ($query) use ($inicio, $fin) {
            $query->where('inicio',     '<=', $inicio)
                  ->where('fin',        '>=', $fin)
                  ->orWhere('inicio',   '<=', $inicio)
                  ->where('fin',        '>=', $inicio)
                  ->orWhere('inicio',   '>=', $inicio)
                  ->where('inicio',     '<=', $fin);
        })
        ->with([ 
            'reservaciones'
        ])
        ->get();
        $idReservados = $reservados->pluck("id");
        $disponibles = self::whereNotIn('id', $idReservados)->get();
        $resultado = $reservados->merge($disponibles);
        return $resultado;
    }
    public static function encontrarPor($fecha){
        $reservados = self::whereHas('reservaciones', function ($query) use ($fecha) {
            $query->whereDate('inicio', '=', $fecha)
                  ->whereDate('fin', '=', $fecha);
        })
        ->with([ 'reservaciones' => function ($query) use ($fecha) {
            $query->whereDate('inicio', '=', $fecha)
                  ->whereDate('fin', '=', $fecha)
                  ->where('idEstado', "=", 2);
        }])
        ->get();
        $idReservados = $reservados->pluck("id");
        $disponibles = self::whereNotIn('id', $idReservados)->get();
        $resultado = $reservados->merge($disponibles);
        return $resultado;
    }
}
