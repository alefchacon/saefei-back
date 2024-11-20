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
        ]);
        User::create([
            'nombres' => 'Karla',
            'apellidoPaterno' => 'Beltrán',
            'email' => 'maledict@protonmail.com',
            'apellidoMaterno' => 'Zamora',
            'puesto' => 'best girl',
        ]);
        User::create([
            'nombres' => 'Esteban',
            'apellidoPaterno' => 'Márquez',
            'email' => 'maledict@proton.me',
            'apellidoMaterno' => 'Gonzáles',
            'puesto' => 'my man',
        ]);
        User::create([
            'nombres' => 'Otra persona',
            'apellidoPaterno' => 'ASDF',
            'email' => 'zs20015745@estudiantes.uv.mx',
            'apellidoMaterno' => 'asdf',
            'puesto' => 'dummy!',
        ]);
    }
}
