<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Organizador', 'Técnico Académico', 'Responsable'];
        foreach ($names as $name) {
            Rol::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}
