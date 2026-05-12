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
    // Permissions métier
    $permissions = [
        'clients.view', 'clients.create', 'clients.edit', 'clients.delete',
        'abonnements.view', 'abonnements.create', 'abonnements.edit', 'abonnements.delete',
        'paiements.view', 'paiements.create', 'paiements.edit', 'paiements.delete',
        'licences.view', 'licences.create', 'licences.edit', 'licences.delete',
        'managers.view', 'managers.create', 'managers.delete', // admin only
    ];

    foreach ($permissions as $perm) {
        Permission::firstOrCreate(['name' => $perm]);
    }

    // Role manager = tout sauf managers.*
    $manager = Role::firstOrCreate(['name' => 'manager']);
    $manager->syncPermissions(
        Permission::whereNotLike('name', 'managers.%')->get()
    );

    // Role admin = tout
    $admin = Role::firstOrCreate(['name' => 'admin']);
    $admin->syncPermissions(Permission::all());
}
}
