<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aviso;
use App\Models\Administrador;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [["nombre" => "ADMINISTRADOR A", "idUsuario" => 2], ["nombre" => "ADMINISTRADOR B", "idUsuario" => 1]];


        foreach ($admins as $admin) {
            Administrador::create([
                'nombre' => $admin["nombre"],
                'idUsuario' => $admin["idUsuario"],
                
            ]);
        }
    }
}
