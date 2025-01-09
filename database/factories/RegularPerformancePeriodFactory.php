<?php

namespace Database\Factories;

use App\Enums\PerformanceEvaluationPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RegularPerformancePeriod>
 */
class RegularPerformancePeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = now();
        $end = fake()->dateTimeBetween($start, (clone $start)->modify('+10 days'));

        return [
            'period_name' => PerformanceEvaluationPeriod::ANNUAL->value,
            'start_date' => $start,
            'end_date' => $end,
        ];
    }
}
