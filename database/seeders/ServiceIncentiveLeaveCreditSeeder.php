<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Applicant;
use App\Models\Application;
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
                $dateHired = $employee->lifecycle->started_at->subDays(rand(7, 30));

                $employee->jobDetail()->update(['hired_at' => $dateHired]);

                $month = $dateHired->month;

                $quarter = match (true) {
                    in_array($month, ServiceIncentiveLeave::getFirstQuarter()) => ServiceIncentiveLeave::Q1,
                    in_array($month, ServiceIncentiveLeave::getSecondQuarter()) => ServiceIncentiveLeave::Q2,
                    in_array($month, ServiceIncentiveLeave::getThirdQuarter()) => ServiceIncentiveLeave::Q3,
                    in_array($month, ServiceIncentiveLeave::getFourthQuarter()) => ServiceIncentiveLeave::Q4,
                };

                $data[] = [
                    'employee_id' => $employee->employee_id,
                    'vacation_leave_credits' => $quarter->value,
                    'sick_leave_credits' => $quarter->value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            } else {
                $month = $employee->application->hired_at->month;

                $quarter = match (true) {
                    in_array($month, ServiceIncentiveLeave::getFirstQuarter()) => ServiceIncentiveLeave::Q1,
                    in_array($month, ServiceIncentiveLeave::getSecondQuarter()) => ServiceIncentiveLeave::Q2,
                    in_array($month, ServiceIncentiveLeave::getThirdQuarter()) => ServiceIncentiveLeave::Q3,
                    in_array($month, ServiceIncentiveLeave::getFourthQuarter()) => ServiceIncentiveLeave::Q4,
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
