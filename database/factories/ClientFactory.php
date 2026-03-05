<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        $first = $this->faker->firstName();
        $last  = $this->faker->lastName();

        return [
            'nom' => $first.' '.$last,

            // email unique (important)
            'email' => $this->faker->unique()->safeEmail(),

            // numéro marocain style 06/07 + 8 chiffres
            'telephone' => $this->faker->randomElement(['06','07']) . $this->faker->numerify('########'),

            'statut_paiement' => $this->faker->randomElement(['payé','en_attente','en_retard']),

            'date_maintenance' => $this->faker->dateTimeBetween('-6 months', '+6 months')->format('Y-m-d'),

            'licences_count' => $this->faker->numberBetween(1, 10),
        ];
    }
}