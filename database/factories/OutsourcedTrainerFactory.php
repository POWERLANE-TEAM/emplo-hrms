<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OutsourcedTrainer>
 */
class OutsourcedTrainerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName,
            'middle_name' => fake()->firstName,
            'last_name' => fake()->lastName,
            'training_provider' => null,
        ];
    }
}
