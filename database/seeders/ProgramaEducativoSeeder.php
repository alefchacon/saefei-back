<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProgramaEducativo;

class ProgramaEducativoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Estadística - Ingeniería en Ciencia de Datos', 
            'Tecnologías Computacionales - Ingeniería en Sistemas y Tecnologías de la Información', 
            'Ingeniería de Software',
            'Redes y Servicios de Cómputo - Ingeniería en Ciberseguridad e Infraestructura de Cómputo',
            'Especialización en Métodos Estadísticos',
            'Maestría en Gestión de Calidad',
            'Maestría en Sistemas Interactivos Centrados en el Usuario',
            'Doctorado en Ciencias de la Computación',
        ];
        foreach ($names as $name) {
            ProgramaEducativo::factory()->create([
                'nombre' => $name,
            ]);
        }
        
    }
}
