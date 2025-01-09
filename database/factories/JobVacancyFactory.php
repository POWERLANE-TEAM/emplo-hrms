<?php

namespace Database\Factories;

use App\Models\JobTitle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobVacancy>
 */
class JobVacancyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id,
            'vacancy_count' => fake()->numberBetween(0, 10),
            'application_deadline_at' => fake()->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
