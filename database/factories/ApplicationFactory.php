<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
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
        $status = fake()->randomElement(array_map(fn ($case) => $case->value, ApplicationStatus::cases()));
        $hiredAt = null;
        $isPassed = false;
        $rejectedAt = now();

        if ($status === ApplicationStatus::APPROVED->value) {
            $hiredAt = now();
            $isPassed = true;
            $rejectedAt = null;
        }

        return [
            'applicant_id' => Applicant::inRandomOrder()->first()->applicant_id,
            'job_vacancy_id' => JobVacancy::factory(),
            'application_status_id' => $status,
            'hired_at' => $hiredAt,
            'is_passed' => $isPassed,
            'rejected_at' => $rejectedAt,
        ];
    }
}
