<?php

namespace Database\Factories;

use App\Enums\TrainingStatus;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'training_date' => Carbon::now()->subDays(rand(1, 30)),
            'training_title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'completion_status' => $this->faker->randomElement(array_map(fn ($case) => $case->value, TrainingStatus::cases())),
            'start_date' => Carbon::now()->subDays(rand(31, 60)),
            'end_date' => Carbon::now()->subDays(rand(1, 30)),
            'expiry_date' => Carbon::now()->addDays(rand(30, 365)),
            'trainee' => null,
            'trainer_type' => null,
            'trainer_id' => null,
            'prepared_by' => null,
            'reviewed_by' => null,
        ];
    }
}
