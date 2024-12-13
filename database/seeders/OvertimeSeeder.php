<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Overtime;
use Illuminate\Database\Seeder;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            Overtime::unguard();
            $overtimes = Overtime::factory(40)->create();
            Overtime::reguard();

            foreach ($overtimes as $overtime) {
                $overtime->processes()->create([
                    'processable_type' => Overtime::class,
                    'processable_id' => $overtime->overtime_id,
                    // 'supervisor_approved_at' => now(),
                    // 'supervisor' => Employee::inRandomOrder()->first()->employee_id,
                    // 'area_manager_approved_at' => now(),
                    // 'area_manager' => Employee::inRandomOrder()->first()->employee_id,
                    // 'hr_manager_approved_at' => now(),
                    // 'hr_manager' => Employee::inRandomOrder()->first()->employee_id,
                ]);
            }
        });
    }
}