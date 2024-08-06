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

        foreach ($ids as $id) {
            Aviso::factory()->create([
                'idEvento' => $id,
            ]);
        }
        foreach ($ids as $id) {
            Aviso::factory()->create([
                'idReservacion' => $id,
            ]);
        }
    }
}
