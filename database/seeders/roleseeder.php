<?php

namespace Database\Seeders;

//use Faker\Guesser\Name;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
///use Spatie\Permission\Commands\AssignRole;
use Spatie\Permission\Models\Role;
//use function Laravel\Prompts\password;

use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        Permission::create(['name' => 'gerer contenu']);
        Permission::create(['name' => 'supprimer manager']);

        
        $admin   = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
                   Role::create(['name' => 'client']);

       
        $admin->givePermissionTo(['gerer contenu', 'supprimer manager']);
        $manager->givePermissionTo(['gerer contenu']);
        
        $user = User::create([
            'name'     => 'admin',
            'email'    => 'administrateur@test.com',
            'password' => bcrypt('password')
        ]);
        $user->assignRole('admin');

        $user = User::create([
            'name'     => 'utilisateur',
            'email'    => 'utilisateur@test.com',
            'password' => bcrypt('motdepasse')
        ]);
        $user->assignRole('client');

        $user = User::create([
            'name'     => 'manager',
            'email'    => 'manager@test.com',
            'password' => bcrypt('password')
        ]);
        $user->assignRole('manager');
    }
}
