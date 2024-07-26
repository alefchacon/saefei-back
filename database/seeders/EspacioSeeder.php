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
        $names = [
            'Auditorio', 
            'Audiovisual', 
            'Salón de Cristal', 
            'Baño',
            'Otro lugar',
        ];

        foreach ($names as $name) {
            Espacio::factory()->create([
                'nombre' => $name,
            ]);
        }

        Espacio::factory()->count(50)->create();
    }
}
