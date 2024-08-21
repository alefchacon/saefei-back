<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aviso;

class AvisoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ids = [1, 2, 3];

        $idTipoAviso_Evento = [1,2,3,4];
        $idTipoAviso_Reservacion = [5,6,7];

        foreach ($ids as $id) {
            Aviso::factory()->create([
                'idEvento' => $id,
                'idTipoAviso' => 1
            ]);
        }
        foreach ($ids as $id) {
            Aviso::factory()->create([
                'idReservacion' => $id,
                'idTipoAviso' => 5
            ]);
        }
    }
}
