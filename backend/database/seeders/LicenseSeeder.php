<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\License;
use App\Models\Client;

class LicenseSeeder extends Seeder
{
    public function run(): void
    {
        $clientIds = Client::pluck('id')->toArray();

        if (empty($clientIds)) {
            $this->command->warn('Aucun client trouvé.');
            return;
        }

        $nomLicenses = ['WP1', 'WP2', 'WP3', 'WP4', 'WP5'];

        $licenses = [];

        foreach ($clientIds as $clientId) {
            $licenses[] = [
                'nom'                 => $nomLicenses[array_rand($nomLicenses)],
                'quantite_disponible' => rand(1, 20),
                'client_id'           => $clientId,
                'date_assignation'    => now()->subDays(rand(1, 730))->format('Y-m-d'),
            ];
        }

        // Insert par chunks pour éviter les problèmes de mémoire
        foreach (array_chunk($licenses, 100) as $chunk) {
            License::insert($chunk);
        }

        $this->command->info(count($licenses) . ' licenses créées avec succès.');
    }
}