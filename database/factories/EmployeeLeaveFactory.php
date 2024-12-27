<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LeaveCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeLeave>
 */
class EmployeeLeaveFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-1 year', 'now');
        $endDate = fake()->dateTimeBetween($startDate, (clone $startDate)->modify('+10 days'));
        $filedAt = fake()->dateTimeBetween('-1 year', $startDate);
        $modifiedAt = fake()->dateTimeBetween($filedAt, 'now');

        return [
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'leave_category_id' => LeaveCategory::inRandomOrder()->first()->leave_category_id,
            'reason' => fake()->paragraph(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'filed_at' => $filedAt,
            'modified_at' => $modifiedAt,
        ];
    }
}
