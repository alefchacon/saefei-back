<?php

namespace Database\Seeders;

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
            TipoArchivoSeeder::class,
            TipoAvisoSeeder::class,
            TipoSeeder::class,
            //RolSeeder::class,
            EstadoSeeder::class,
            ModalidadSeeder::class,
            //UserSeeder::class,
            UserRolSeeder::class,
            AdministradorSeeder::class,
            EspacioSeeder::class,
            ReservacionSeeder::class,
            EventoSeeder::class,
            ProgramaEducativoSeeder::class,
            AvisoSeeder::class,
        ]);
    }
}
