<?php

namespace Database\Factories;

use App\Models\Payroll;
use App\Models\Employee;
use App\Models\OvertimePayrollApproval;
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
        $payrolls = Payroll::all();
        $payroll = $payrolls->random();

        $start = fake()->dateTimeBetween($payroll->cut_off_start, $payroll->cut_off_end);
        $end = (clone $start)->modify('+'.rand(1, 4).' hours');

        if ($end <= $start) {
            $end = (clone $start)->modify('+1 hour');
        }
        $payrollApproval = OvertimePayrollApproval::firstOrCreate([
            'payroll_id' => $payroll->payroll_id,
        ]);

        return [
            'employee_id' => Employee::inRandomOrder()->first()->employee_id,
            'payroll_approval_id' => $payrollApproval->payroll_approval_id,
            'work_performed' => fake()->randomElement([
                'Project work', 
                'Emergency task', 
                'Client meeting', 
                'Report preparation', 
                'System upgrade', 
                'Training session'
            ]),
            'date' => $start->format('Y-m-d'),
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'filed_at' => $start,
            'modified_at' => $start,
        ];
    }
}
