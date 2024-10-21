<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoAviso;

class TipoAvisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Nuevo evento', 
            'Evento aceptado', 
            'Evento editado', 
            'Evento evaluado', 
            'Nueva solicitud de reservación', 
            'Reservación aceptada', 
            'Reservación rechazada'];

        foreach ($names as $name) {
            TipoAviso::factory()->create([
                'nombre' => $name,
            ]);
        }
    }
}
