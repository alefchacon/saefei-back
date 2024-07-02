<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SolicitudEspacio;
require 'vendor/autoload.php';

class SolicitudEspacioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date1 = new \DateTime();
        $date2 = clone $date1;
        $date2->modify('+3 days');
        
        $date3 = clone $date1;
        $date3->modify('+6 days');
        
        $date4 = clone $date1;
        $date4->modify('+9 days');

        $dates = [$date1, $date2, $date3, $date4];

        foreach ($dates as $date) {
            SolicitudEspacio::factory()->create([
                'inicio' => $date,
                'fin' => $date,
                'idEstado' => 1
            ]);
        }
    }
}
