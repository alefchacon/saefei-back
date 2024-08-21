<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nombres' => 'Alejandro',
            'apellidoPaterno' => 'Chacon',
            'email' => 'vitocfdz@gmail.com',
            'apellidoMaterno' => 'Fernandez',
            'puesto' => 'best boy',
            'idRol' => 5,
        ]);
        User::create([
            'nombres' => 'Karla',
            'apellidoPaterno' => 'Beltrán',
            'email' => 'maledict@protonmail.com',
            'apellidoMaterno' => 'Zamora',
            'puesto' => 'best girl',
            'idRol' => 1,
        ]);
        User::create([
            'nombres' => 'Esteban',
            'apellidoPaterno' => 'Márquez',
            'email' => 'maledict@proton.me',
            'apellidoMaterno' => 'Gonzáles',
            'puesto' => 'my man',
            'idRol' => 2,
        ]);
    }
}
