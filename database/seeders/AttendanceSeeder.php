<?php

namespace Database\Seeders;

use App\Enums\BiometricPunchType;
use App\Models\AttendanceLog;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;

function createAttendances(array $employeeIds, int $partitionIndex, Carbon $currentDate): void
{
    $employees = Employee::with('shift')->whereIn('employee_id', $employeeIds)->get();

    activity()->withoutLogs(function () use ($employees, $partitionIndex, $currentDate) {
        static $uid = 0;

        foreach ($employees as $employee) {
            $shift = $employee->shift;
            $shiftStart = $shift->start_time;
            $shiftEnd = $shift->end_time;

            $randomMinutes = rand(-10, 30);
            $timeIn = Carbon::parse($shiftStart)->addMinutes($randomMinutes)->setDateFrom($currentDate);



            DB::table('attendance_logs')->insert([
                // 'uid' => $uid++,
                'employee_id' => $employee->employee_id,
                'state' => 3,
                'type' => BiometricPunchType::CHECK_IN->value,
                'timestamp' => $timeIn,
            ]);

            if (rand(0, 1)) {
                $timeOut = Carbon::parse($shiftEnd)->addMinutes($randomMinutes)->setDateFrom($currentDate);

                DB::table('attendance_logs')->insert([
                    // 'uid' => $uid++,
                    'employee_id' => $employee->employee_id,
                    'state' => 3,
                    'type' => BiometricPunchType::CHECK_OUT->value,
                    'timestamp' => $timeOut,
                ]);
            } else {
                // Overtime

                $timeOut = Carbon::parse($shiftEnd)->addMinutes($randomMinutes);

                DB::table('attendance_logs')->insert([
                    // 'uid' => $uid++,
                    'employee_id' => $employee->employee_id,
                    'state' => 3,
                    'type' => BiometricPunchType::OVERTIME_IN->value,
                    'timestamp' => $timeOut
                ]);


                DB::table('attendance_logs')->insert([
                    // 'uid' => $uid++,
                    'employee_id' => $employee->employee_id,
                    'state' => 3,
                    'type' => BiometricPunchType::OVERTIME_OUT->value,
                    'timestamp' => $timeOut->copy()->addHours(8)->setDateFrom($currentDate),
                ]);
            }
        }
    });
}

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $startDate = '1992-03-01';
        $startDate = now()->subMonth(1);
        $endDate = now();
        $currentDate = Carbon::parse($startDate);

        // Get employee IDs instead of full models
        $employeeIds = Employee::has('jobDetail')->pluck('employee_id')->toArray();

        $concurrencyCount = env('APP_MAX_CONCURRENT_COUNT', 10);

        $partitions = array_chunk($employeeIds, ceil(count($employeeIds) / $concurrencyCount));

        AttendanceLog::unguard();

        while ($currentDate->lte($endDate)) {
            $tasks = [];
            $dateForClosure = $currentDate->copy();

            foreach ($partitions as $partitionIndex => $partition) {
                $tasks[] = fn() => createAttendances($partition, $partitionIndex, $dateForClosure);
            }

            Concurrency::run($tasks);

            $currentDate->addDay();
        }

        AttendanceLog::reguard();
    }
}
