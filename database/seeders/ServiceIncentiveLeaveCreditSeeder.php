<?php

namespace Database\Seeders;

use App\Models\Employee;
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
        $employees = Employee::all();

        $data = [];
        $month = null;

        $employees->each(function ($employee) use (&$data, &$month) {
            $month = $employee->application?->hired_at?->month;

            if (! $month) {
                $dateHired = $employee->lifecycle->started_at->subDays(rand(7, 30));

                $employee->jobDetail()->update(['hired_at' => $dateHired]);

                $month = $dateHired->month;
            }

            $result = array_column(
                array_filter(
                    ServiceIncentiveLeave::getAllQuartersAndCredits(), 
                    fn ($qCred) => in_array($dateHired->month, $qCred['months'])
                ), 'credits'
            );
            
            $credits = reset($result);

            $data[] = [
                'employee_id' => $employee->employee_id,
                'vacation_leave_credits' => $credits,
                'sick_leave_credits' => $credits,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        DB::table('service_incentive_leave_credits')->insert($data);
    }
}
