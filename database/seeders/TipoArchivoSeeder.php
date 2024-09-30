<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoArchivo;

class TipoArchivoSeeder extends Seeder
{
    public function run(): void
    {
        $names = ['CRONOGRAMA', 'EVIDENCIA', 'PUBLICIDAD'];
        foreach ($names as $name) {
            TipoArchivo::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}
