<?php

namespace Database\Seeders;

use App\Models\Plataforma;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlataformaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $names = ['Zoom', 'Teams', 'Google Meet'];
        foreach ($names as $name) {
            Plataforma::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}
