<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'montant'         => $this->faker->randomFloat(2, 10, 5000),
            'date_payment'    => $this->faker->date(),
            'status_payment'  => $this->faker->randomElement(['payé', 'en_attente', 'en_retard']),
            'client_id'       => Client::inRandomOrder()->first()?->id
                                 ?? Client::factory()->create()->id,
        ];
    }
}