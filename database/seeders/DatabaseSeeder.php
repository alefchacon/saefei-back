<?php

namespace Database\Seeders;

use App\Models\Plataforma;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            TipoAvisoSeeder::class,
            EspacioSeeder::class,
            TipoSeeder::class,
            RolSeeder::class,
            EstadoSeeder::class,
            ModalidadSeeder::class,
            UserSeeder::class,
            EventoSeeder::class,
            ReservacionSeeder::class,
            ProgramaEducativoSeeder::class,
            AvisoSeeder::class,
        ]);
    }
}
