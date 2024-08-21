<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Modalidad;

class ModalidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Presencial', 'Virtual', 'Híbrida/Mixta'];
        foreach ($names as $name) {
            Modalidad::factory()->create([
                'nombre' => $name,
            ]);
        }
        
    }
}
