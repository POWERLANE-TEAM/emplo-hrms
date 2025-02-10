<?php

namespace Database\Factories;

use App\Models\Shift;
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
        $shifts = Shift::all();
        $shift = $shifts->random();

        $start = fake()->dateTimeBetween('22:00:00', '23:59:00');
        $end = (clone $start)->modify('+'.rand(1, 4).' hours');

        if ($end <= $start) {
            $end = (clone $start)->modify('+1 hour');
        }

        $payroll = Payroll::all()->random();
        
        $payrollApproval = OvertimePayrollApproval::firstOrCreate([
            'payroll_id' => $payroll->payroll_id,
        ]);

        return [
            'employee_id' => Employee::activeEmploymentStatus()->inRandomOrder()->first()->employee_id,
            'payroll_approval_id' => $payrollApproval->payroll_approval_id,
            'work_performed' => fake()->randomElement([
                'Project work', 
                'Emergency task', 
                'Client meeting', 
                'Report preparation', 
                'System upgrade', 
                'Training session'
            ]),
            'start_time' => $start,
            'end_time' => $end,
            // 'authorizer_signed_at' => now(),
            'filed_at' => $start,
            'modified_at' => $start,
        ];
    }
}
