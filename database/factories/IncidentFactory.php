<?php

namespace Database\Factories;

use App\Models\Issue;
use App\Models\Employee;
use App\Enums\IssueStatus;
use App\Enums\IncidentPriorityLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incident>
 */
class IncidentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'incident_description' => fake()->paragraph(),
            'status' => fake()->randomElement(array_map(fn ($case) => $case->value, IssueStatus::cases())),
            'priority' => fake()->randomElement(array_map(fn ($case) => $case->value, IncidentPriorityLevel::cases())),
            'initiator' => Employee::inRandomOrder()->first()->employee_id,
            'reporter' => Employee::inRandomOrder()->first()->employee_id,
            'originator' => Issue::inRandomOrder()?->first()?->issue_id,
        ];
    }
}
