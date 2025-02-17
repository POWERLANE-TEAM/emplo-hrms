<?php

namespace Database\Seeders;

use App\Enums\BiometricPunchType;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AttendanceLog2024Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $holidays = Holiday::pluck('date')->toArray();

        $employees = Employee::activeEmploymentStatus()->get();

        foreach ($employees as $employee) {
            for ($day = 1; $day <= 365; $day++) {
                $date = Carbon::create(2024, 1, 1)->addDays($day - 1);

                if ($date->isWeekend() || in_array($date->format('m-d'), $holidays)) {
                    continue;
                }

                if ($this->shouldBeAbsent($employee)) {
                    continue;
                }

                $this->generateAttendanceForEmployee($employee, $date);
            }
        }
    }

    /**
     * Simulate a random chance of being absent for an employee.
     *
     * @param  Employee  $employee
     * @return bool
     */
    private function shouldBeAbsent($employee)
    {
        return rand(0, 100) < 5;
    }

    /**
     * Generate attendance logs (check-in and check-out) for an employee on a specific date.
     */
    private function generateAttendanceForEmployee($employee, $date)
    {
        $checkInTime = $this->generateTimestampForEmployee($date, BiometricPunchType::CHECK_IN);
        $checkOutTime = $this->generateTimestampForEmployee($date, BiometricPunchType::CHECK_OUT);

        AttendanceLog::factory()->create([
            'employee_id' => $employee->employee_id,
            'state' => 1,
            'type' => BiometricPunchType::CHECK_IN->value,
            'timestamp' => $checkInTime,
        ]);

        AttendanceLog::factory()->create([
            'employee_id' => $employee->employee_id,
            'state' => 2,
            'type' => BiometricPunchType::CHECK_OUT->value,
            'timestamp' => $checkOutTime,
        ]);
    }

    /**
     * Generate a fake timestamp for check-in or check-out for a specific date.
     */
    private function generateTimestampForEmployee($date, $type)
    {
        $timeRange = ($type == BiometricPunchType::CHECK_IN->value)
            ? ['08:00', '10:00']
            : ['16:00', '18:00'];

        return Carbon::parse($date->format('Y-m-d').' '.$this->randomTimeInRange($timeRange))->format('Y-m-d H:i:s');
    }

    /**
     * Generate a random time between two given time ranges.
     */
    private function randomTimeInRange($range)
    {
        $start = Carbon::parse($range[0]);
        $end = Carbon::parse($range[1]);

        $randomTime = $start->copy()->addSeconds(rand(0, $end->diffInSeconds($start)));

        return $randomTime->format('H:i');
    }
}
