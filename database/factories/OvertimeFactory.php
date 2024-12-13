<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Overtime>
 */
class OvertimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-2 days', 'now');
        $end = fake()->dateTimeBetween($start, '+4 hours');

        return [
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'work_performed' => fake()->sentence(),
            'start_time' => $start,
            'end_time' => $end,
            'filed_at' =>  fake()->dateTimeBetween('-1 month', $end),
        ];
    }
}
