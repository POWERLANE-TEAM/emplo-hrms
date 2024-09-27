<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobLevel>
 */
class JobLevelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_level' => fake()->numberBetween(1, 10),
            'job_level_name' => fake()->jobTitle,
            'job_level_desc' => fake()->optional()->paragraph,
        ];
    }
}