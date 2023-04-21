<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supply>
 */
class SupplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->randomElement(['supplyOne', 'supplyTwo', 'supplyThree']),
            'amount' => fake()->numberBetween($min = 1, $max = 20),
            'quantity' => fake()->numberBetween($min = 1, $min = 50),
            'unit' => fake()->randomElement(['kg', 'gal', 'ml'])
        ];
    }
}
