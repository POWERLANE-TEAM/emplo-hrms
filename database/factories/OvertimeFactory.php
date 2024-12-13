<?php

namespace Database\Factories;

use App\Enums\Payroll;
use App\Models\Employee;
use Illuminate\Support\Carbon;
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
        $start = fake()->dateTimeBetween('-15 days', 'now');
        $end = fake()->dateTimeBetween($start, '+4 hours');
    
        $date = Carbon::parse($start);

        $cutOffPeriod = match (true) {
            $date->day <= 10 => Payroll::CUT_OFF_2,
            $date->day <= 25 => Payroll::CUT_OFF_1,
            default => Payroll::CUT_OFF_2,
        };
    
        return [
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'work_performed' => fake()->sentence(),
            'date' => $start->format('Y-m-d'),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'cut_off' => $cutOffPeriod->value,
            'filed_at' => $start,
            'modified_at' => $start,
        ];
    }    
}
