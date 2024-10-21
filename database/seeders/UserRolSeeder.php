<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Rol;

class UserRolSeeder extends Seeder
{
    public function run()
    {
        // First, ensure you have some roles
        $roles = [
            ['nombre' => 'COORDINADOR'],
            ['nombre' => 'ADMINISTRADOR'],
        ];

        foreach ($roles as $role) {
            Rol::firstOrCreate($role);
        }

        // Now, create users and assign roles
        $users = [
            [
                'nombres' => 'Alejandro',
                'apellidoPaterno' => 'Chacon',
                'email' => 'vitocfdz@gmail.com',
                'apellidoMaterno' => 'Fernandez',
                'puesto' => 'best boy',
                'roles' => ['COORDINADOR', 'ADMINISTRADOR']
            ],
            [
                'nombres' => 'Karla',
                'apellidoPaterno' => 'Beltrán',
                'email' => 'maledict@protonmail.com',
                'apellidoMaterno' => 'Zamora',
                'puesto' => 'best girl',
                'roles' => []
            ],
            [
                'nombres' => 'Esteban',
                'apellidoPaterno' => 'Márquez',
                'email' => 'maledict@proton.me',
                'apellidoMaterno' => 'Gonzáles',
                'puesto' => 'my man',
                'roles' => ['ADMINISTRADOR']
            ],
        ];

        foreach ($users as $userData) {
            $roles = $userData['roles'];
            unset($userData['roles']);

            $user = User::create($userData);
            
            foreach ($roles as $roleName) {
                $role = Rol::where('nombre', $roleName)->first();
                $user->roles()->attach($role->id);
            }
        }
    }
}