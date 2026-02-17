<?php

namespace Database\Seeders;

use Faker\Guesser\Name;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Commands\AssignRole;
use Spatie\Permission\Models\Role;
use function Laravel\Prompts\password;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

//jai cree les roles 
    Role::create(['name'=>'admin']);

    Role::create(['name'=>'manager']);

    Role::create(['name'=>'client']);

//ensuite j ai attribuer les roles pour des utilisateurs
    $user = User::create([
        'name'=>'admin',

        'email'=>'administrateur@test.com',

        'password'=>bcrypt('password')
    
    ]);
    $user->assignRole('admin');


    $user = User::create(
        [

            'name'=>'utilisateur',

            'email'=>'utilisateur@test.com',

            'password'=>bcrypt('motdepasse')
        ]
    );
    $user->assignRole('client');

    $user=User::create(
        [
            'name'=>'manager',
            'email'=>'manager@test.com',
            'password'=>bcrypt('password')
        ]
    );
    $user->assignRole('manager');


    
    }
}
