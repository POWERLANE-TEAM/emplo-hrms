<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Applicant;
use App\Models\Application;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Enums\ServiceIncentiveLeave;

class ServiceIncentiveLeaveCreditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Applicant::unguard();
        Application::unguard();
        activity()->disableLogging();

        $employees = Employee::all();

        $data = [];

        $employees->each(function ($employee) use (&$data) {
            if (! $employee->application) {
                $dateHired = Carbon::parse($employee->lifecycle->started_at)->subDays(rand(7, 30));

                $employee->jobDetail()->update(['hired_at' => $dateHired]);

                $month = $dateHired->copy()->month;

                $quarter = match (true) {
                    in_array($month, ServiceIncentiveLeave::getFirstQuarter()) => ServiceIncentiveLeave::Q1,
                    in_array($month, ServiceIncentiveLeave::getSecondQuarter()) => ServiceIncentiveLeave::Q2,
                    in_array($month, ServiceIncentiveLeave::getThirdQuarter()) => ServiceIncentiveLeave::Q3,
                    in_array($month, ServiceIncentiveLeave::getFourthQuarter()) => ServiceIncentiveLeave::Q4,
                };

                $increase = 0;
                $now = now();

                // => 4 yrs and <= 5 > yrs is increase of 12
                if (
                    $dateHired->copy()->between($now->copy()->subYears(5), $now->copy()->subYears(4), true) ||
                    $dateHired->copy()->lessThan($now->copy()->subYears(5))
                ) {
                    $increase = 12;

                // => 3 yrs and < 4 yrs is increase of 10
                } else if ($dateHired->copy()->between($now->copy()->subYears(4), $now->copy()->subYears(3), false)) {
                    $increase = 10;

                // => 2 yrs and < 3 yrs is increase of 7
                } else if ($dateHired->copy()->between($now->copy()->subYears(3), $now->copy()->subYears(2), false)) {
                    $increase = 7;

                // => 1 yr and < 2 yrs is increase of 5
                } else if ($dateHired->copy()->between($now->copy()->subYears(2), $now->copy()->subYear(), false)) {
                    $increase = 5;
                }

                $data[] = [
                    'employee_id' => $employee->employee_id,
                    'vacation_leave_credits' => $quarter->value,
                    'sick_leave_credits' => $quarter->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                $month = Carbon::parse($employee->application->hired_at);

                $quarter = match (true) {
                    in_array($month, ServiceIncentiveLeave::Q1->getFirstQuarter()) => ServiceIncentiveLeave::Q1,
                    in_array($month, ServiceIncentiveLeave::Q2->getSecondQuarter()) => ServiceIncentiveLeave::Q2,
                    in_array($month, ServiceIncentiveLeave::Q3->getThirdQuarter()) => ServiceIncentiveLeave::Q3,
                    in_array($month, ServiceIncentiveLeave::Q4->getFourthQuarter()) => ServiceIncentiveLeave::Q4,
                };

                $data[] = [
                    'employee_id' => $employee->employee_id,
                    'vacation_leave_credits' => $quarter->value,
                    'sick_leave_credits' => $quarter->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        });

        DB::table('service_incentive_leave_credits')->insert($data);

        activity()->enableLogging();
        Applicant::reguard();
        Application::reguard();
    }
}
