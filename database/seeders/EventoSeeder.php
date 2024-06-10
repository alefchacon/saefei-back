<?php

namespace Database\Seeders;

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
        //Evento::factory()->count(10)->create();
        Evento::factory()->create([
            'nombre' => "Seminario de Investigación de Ingeniería de Software",
            'idUsuario' => 1
        ]);
        Evento::factory()->create([
            'nombre' => "Seminario de otra cosa idk",
            'idUsuario' => 1
        ]);
        Evento::factory()->create([
            'nombre' => "Coloquio de Sistemas Centrados en el Usuario",
            'idUsuario' => 2
        ]);
        Evento::factory()->count(5)->hasEvaluacion(1)->create();
        Evento::factory()->count(20)->hasEvaluacion(0)->create();

    }
}
