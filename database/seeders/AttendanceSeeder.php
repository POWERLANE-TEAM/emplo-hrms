<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

function createAttendances(array $employeeIds, int $partitionIndex, Carbon $currentDate): void
{
    $employees = Employee::with('shift')->whereIn('employee_id', $employeeIds)->get();

    foreach ($employees as $employee) {
        $shift = $employee->shift;
        $shiftStart = $shift->start_time;
        $shiftEnd = $shift->end_time;

        $randomMinutes = rand(-10, 30);
        $timeIn = Carbon::parse($shiftStart)->addMinutes($randomMinutes)->setDateFrom($currentDate);

        if (rand(0, 1)) {
            $timeOut = Carbon::parse($shiftEnd)->addMinutes($randomMinutes)->setDateFrom($currentDate);
        } else {
            // Overtime
            $timeOut = Carbon::parse($shiftEnd)->addMinutes($randomMinutes)->addHours(8)->setDateFrom($currentDate);
        }


        DB::table('attendances')->insert([
            'employee_id' => $employee->employee_id,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
        ]);
    }
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
        $employeeIds = Employee::pluck('employee_id')->toArray();

        $concurrencyCount = env('APP_MAX_CONCURRENT_COUNT', 10);

        $partitions = array_chunk($employeeIds, ceil(count($employeeIds) / $concurrencyCount));

        Attendance::unguard();

        while ($currentDate->lte($endDate)) {
            $tasks = [];
            $dateForClosure = $currentDate->copy();

            foreach ($partitions as $partitionIndex => $partition) {
                $tasks[] = fn() => createAttendances($partition, $partitionIndex, $dateForClosure);
            }

            Concurrency::run($tasks);

            $currentDate->addDay();
        }

        Attendance::reguard();
    }
}
