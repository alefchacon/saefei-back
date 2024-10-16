<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Rol;

class RolSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['ORGANIZADOR', 'COORDINADOR', 'ADMIN_A', 'ADMIN_B'];
        foreach ($names as $name) {
            Rol::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}
