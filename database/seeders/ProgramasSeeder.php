<?php

namespace Database\Seeders;

use App\Models\ProgramaEducativo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Tecnologias Computacionales', 'Ingenieria de Software', 'Economia', 'Redes', 'Geografia'];
        foreach ($names as $name) {
        ProgramaEducativo::factory()->create([
            'nombre' => $name,
        ]);
    }

    }
}
