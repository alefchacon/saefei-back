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
            'email' => 'asdf@asdf.com',
            'apellidoMaterno' => 'Fernandez',
            'puesto' => 'best boy',
            //'password' => Hash::make('asdf'),
            'idRol' => 3,
        ]);
    }
}
