<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Reservacion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Evento;

class EventoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        //Evento::factory()->count(10)->create();
        Evento::factory()
            ->count(10) // Create 10 events
            ->hasReservaciones(
                Reservacion::factory()
                    ->count(5) // Each event has 5 reservations
                    ->hasActividades(
                        Actividad::factory()
                            ->count(3) // Each reservation has 3 activities
                    )
            )
            ->create([
                'nombre' => "Seminario de Investigación de Ingeniería de Software",
                "tematicas" => "Biodiversidad e integridad ecosistémica;Disciplinar;Estilos de vida y patrones de consumo",
                "audiencias" => "Académicos;Personal Administrativo;Estudiantes;Público en general",
                'idUsuario' => 1,
            ]);
        Evento::factory()
            ->count(10) // Create 10 events
            ->hasReservaciones(
                Reservacion::factory()
                    ->count(5) // Each event has 5 reservations
                    ->hasActividades(
                        Actividad::factory()
                            ->count(3) // Each reservation has 3 activities
                    )
            )
            ->create([
                'nombre' => "Seminario de otra cosa idk",
                'idUsuario' => 1,
            ]);
        Evento::factory()
            ->count(10) // Create 10 events
            ->hasReservaciones(
                Reservacion::factory()
                    ->count(5) // Each event has 5 reservations
                    ->hasActividades(
                        Actividad::factory()
                            ->count(3) // Each reservation has 3 activities
                    )
            )
            ->create([
                'nombre' => "Coloquio de Sistemas Centrados en el Usuario",
                'idUsuario' => 2,
            ]);
            */
        Evento::factory()
            ->count(40)
            ->has(
                Reservacion::factory()
                    ->state([
                        'idEstado' => 3, // Override default values here
                    ])
                    ->count(1)
                    ->has(
                        Actividad::factory()
                            ->count(2),
                            'actividades'
                    ),
                    'reservaciones'
            )
            ->create();

            $names = [
                "Seminario de Investigación de Ingeniería de Software",
                "Foro de Divulgación Cientfífica de Ciencias Computacionales",
                "Coloquio de Sistemas Centrados en el Usuario",
                "Exposición de perritos",
                "Taller 'El Reggaeton y sus estragos en la sociedad'"
            ];

            foreach ($names as $name) {
                Evento::factory()
                ->state(["nombre" => $name])
                ->count(1)
                ->has(
                    Reservacion::factory()
                        ->state([
                            'idEstado' => 3, // Override default values here
                            "fecha" => "2024-12-01"
                        ])
                        ->count(1)
                        ->has(
                            Actividad::factory()
                                ->count(2),
                                'actividades'
                        ),
                        'reservaciones'
                )
                ->create();
            }


    }
}
