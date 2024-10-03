<?php

namespace Database\Factories;

use App\Models\JobDetail;
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
            'job_detail_id' => JobDetail::factory(),
            'vacancy_count' => fake()->numberBetween(1, 10),
            'application_deadline_at' => fake()->optional()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
