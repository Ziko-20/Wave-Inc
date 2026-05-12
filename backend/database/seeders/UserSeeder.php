<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'manager']);
        Role::firstOrCreate(['name' => 'client']);

        // ── Admin ────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->syncRoles('admin');

        // ── Manager ──────────────────────────────────────────────
        $manager = User::firstOrCreate(
            ['email' => 'manager@test.com'],
            [
                'name'     => 'Manager',
                'password' => Hash::make('password'),
            ]
        );
        $manager->syncRoles('manager');

        // ── Client ───────────────────────────────────────────────
        $clientUser = User::firstOrCreate(
            ['email' => 'client@test.com'],
            [
                'name'     => 'Client Test',
                'password' => Hash::make('password'),
            ]
        );
        $clientUser->syncRoles('client');

        // Link to a Client profile (create one if none linked)
        if (! $clientUser->client) {
            Client::create([
                'user_id'          => $clientUser->id,
                'nom'              => 'Client Test',
                'email'            => 'client@test.com',
                'telephone'        => '0600000000',
                'statut_paiement'  => 'en_attente',
                'date_maintenance' => now()->addYear(),
                'licences_count'   => 1,
            ]);
        }
    }
}
