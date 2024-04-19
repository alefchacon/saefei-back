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
            'password' => Hash::make('asdf'),
            'idRol' => 3,
        ]);
        User::create([
            'nombres' => 'Alejandro',
            'apellidoPaterno' => 'Chacon',
            'email' => 'qwer@qwer.com',
            'apellidoMaterno' => 'Fernandez',
            'password' => Hash::make('qwer'),
            'idRol' => 3,
        ]);
    }
}
