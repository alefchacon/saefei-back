<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tipo;

class TipoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Académico', 'Deportivo', 'Cultural', 'Híbrido/Mixto'];

        foreach ($names as $name) {
            Tipo::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}
