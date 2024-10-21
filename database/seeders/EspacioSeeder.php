<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Espacio;

class EspacioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Espacio::create([
            'nombre' => 'Auditorio',
            'capacidad' => 120,
            'idAdministrador' => 1
        ]);
        Espacio::create([
            'nombre' => 'Audiovisual',
            'capacidad' => 80,
            'idAdministrador' => 1
        ]);
        Espacio::create([
            'nombre' => 'Salón de Cristal',
            'capacidad' => 150,
            'idAdministrador' => 2
        ]);
        Espacio::create([
            'nombre' => 'Sala de maestros',
            'capacidad' => 30,
            'idAdministrador' => 1
        ]);
        Espacio::create([
            'nombre' => 'Teatro al aire libre',
            'capacidad' => 90,
            'idAdministrador' => 2
        ]);

    }
}
