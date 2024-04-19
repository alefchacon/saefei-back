<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Estado;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['En revisión', 'Aceptado', 'Evaluado', 'Rechazado'];

        foreach ($names as $name) {
            Estado::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}