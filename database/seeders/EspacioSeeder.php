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
        $names = ['Auditorio', 'Audiovisual', 'Salón de Cristal', 'Baño'];

        foreach ($names as $name) {
            Espacio::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}
