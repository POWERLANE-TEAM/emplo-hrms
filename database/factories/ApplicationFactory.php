<?php

namespace Database\Factories;

use App\Models\Applicant;
use App\Models\JobVacancy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'applicant_id' => Applicant::factory(),
            'job_vacancy_id' => JobVacancy::factory(),
            'application_status_id' => 2, // approved or hired
            'hired_at' => now(),
        ];
    }
}
