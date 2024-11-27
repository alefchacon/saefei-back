<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservacion;
require 'vendor/autoload.php';

class ReservacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date1 = new \DateTime();
        $date2 = clone $date1;
        $date2->modify('+3 days');
        
        $date3 = clone $date1;
        $date3->modify('+6 days');
        
        $date4 = clone $date1;
        $date4->modify('+9 days');

        $dates = [$date1, $date2, $date3];

        /*
        foreach ($dates as $date) {
            Reservacion::factory()->hasRespuesta()->hasActividades(5)->create([
                'fecha' => $date,
                'inicio' => $date,
                'fin' => $date,
                
            ]);
        }
            */

        Reservacion::create([
            "fecha" => "2024-11-15",
            "inicio" => "2024-11-15 10:00",
            "fin" => "2024-11-15 12:00",
            "motivo" => "Seminario de investigación en ingeniería de software",
            "idUsuario" => 2,
            "idEspacio" => 2,
            "idEstado" => 1,
        ]);
        Reservacion::create([
            "fecha" => "2024-12-23",
            "inicio" => "2024-12-23 07:00",
            "fin" => "2024-12-23 11:30",
            "motivo" => "Conferencia de Claudia Sheinbaum",
            "idUsuario" => 1,
            "idEspacio" => 2,
            "idEstado" => 1,
        ]);
        Reservacion::create([
            "fecha" => "2024-12-27",
            "inicio" => "2024-12-27 10:00",
            "fin" => "2024-12-27 16:00",
            "motivo" => "Foro de divulgación científica de estadística",
            "idUsuario" => 3,
            "idEspacio" => 1,
            "idEstado" => 1,
        ]);
        Reservacion::create([
            "fecha" => "2024-12-12",
            "inicio" => "2024-12-12 10:00",
            "fin" => "2024-12-12 16:00",
            "motivo" => "PRUEBA",
            "idUsuario" => 1,
            "idEspacio" => 2,
            "idEstado" => 2,
        ]);
        Reservacion::create([
            "fecha" => "2024-12-12",
            "inicio" => "2024-12-12 11:00",
            "fin" => "2024-12-12 18:30",
            "motivo" => "PRUEBA",
            "idUsuario" => 1,
            "idEspacio" => 1,
            "idEstado" => 2,
        ]);
        Reservacion::create([
            "fecha" => "2024-12-12",
            "inicio" => "2024-12-12 12:00",
            "fin" => "2024-12-12 18:00",
            "motivo" => "PRUEBA",
            "idUsuario" => 1,
            "idEspacio" => 2,
            "idEstado" => 2,
        ]);
    }
}
