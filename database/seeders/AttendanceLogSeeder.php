<?php

namespace Database\Seeders;

use App\Enums\BiometricPunchType;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceLogSeeder extends Seeder
{
    private $mockData = [];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::activeEmploymentStatus()
            ->get()
            ->each(function ($employee) {
                $checkIn = [
                    'type' => BiometricPunchType::CHECK_IN->value,
                    'timestamp' => fake()->dateTimeBetween('5:30', '14:00')->format('Y-m-d H:i:s'),
                ];
                $checkOut = [
                    'type' => BiometricPunchType::CHECK_OUT->value,
                    'timestamp' => fake()->dateTimeBetween('13:30', '22:00')->format('Y-m-d H:i:s'),
                ];

                for ($i = 2; $i > 0; $i--) {
                    $this->mockData[] = [
                        'employee_id' => $employee->employee_id,
                        'state' => fake()->numberBetween(1, 9),
                        'type' => $i === 2 ? $checkIn['type'] : $checkOut['type'],
                        'timestamp' => $i === 2 ? $checkIn['timestamp'] : $checkOut['timestamp'],
                    ];
                }
            });

        DB::table('attendance_logs')->insert($this->mockData);
    }
}
