<?php

namespace Database\Factories;

use App\Enums\IssueConfidentiality;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Issue>
 */
class IssueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'issue_reporter' => Employee::inRandomOrder()->first()->employee_id,
            'confidentiality' => fake()->randomElement(array_map(fn ($case) => $case->value, IssueConfidentiality::cases())),
            'occured_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'issue_description' => fake()->paragraph(),
            'desired_resolution' => fake()->paragraph(),
            'status_marked_at' => now(),
        ];
    }
}
