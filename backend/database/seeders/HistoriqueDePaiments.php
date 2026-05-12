<?php

namespace Database\Seeders;
use App\Models\Payment;
use App\Models\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HistoriqueDePaiments extends Seeder
{
    /**
     * Run the database seeds.
     */
   /*  public function run(): void
    {
        Payment::factory()->count(1000)->create();
    } */
   public function run(): void
    {
        $clientIds = Client::pluck('id');

        $clientIds->each(function ($clientId) {
            Payment::factory()
                ->count(rand(1, 5))
                ->create(['client_id' => $clientId]);
        });

        $this->command->info('Historique des paiements créé avec succès.');
    }
}
