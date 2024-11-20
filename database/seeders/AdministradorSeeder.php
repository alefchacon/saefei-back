<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aviso;
use App\Models\Administrador;
use Illuminate\Support\Facades\DB;


class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = ["ADMINISTRADOR A","ADMINISTRADOR B"];


        foreach ($admins as $admin) {
            Administrador::create([
                'nombre' => $admin
            ]);
        }

        DB::insert('INSERT INTO users_administradores (idUsuario, idAdministrador) VALUES (?, ?)', [3, 1]);
        DB::insert('INSERT INTO users_administradores (idUsuario, idAdministrador) VALUES (?, ?)', [4, 1]);
        DB::insert('INSERT INTO users_administradores (idUsuario, idAdministrador) VALUES (?, ?)', [1, 2]);

    }
}
