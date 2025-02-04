<?php

namespace Database\Seeders;

use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollSummarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mockData = [];

        Employee::select('employee_id')->each(function ($employee) use (&$mockData) {
            Payroll::select(['payroll_id', 'cut_off_end',])->each(function ($payroll) use ($employee, &$mockData) {
                array_push($mockData, [
                    'employee_id'           => $employee->employee_id,
                    'payroll_id'            => $payroll->payroll_id,
                    'reg_hrs'               => rand(100, 200),
                    'reg_nd'                => rand(100, 200),
                    'reg_ot'                => rand(100, 200),
                    'reg_ot_nd'             => rand(100, 200),
                    'rest_hrs'              => rand(100, 200),
                    'rest_nd'               => rand(100, 200),
                    'rest_ot'               => rand(100, 200),
                    'rest_ot_nd'            => rand(100, 200),
                    'reg_hol_hrs'           => rand(100, 200),
                    'reg_hol_nd'            => rand(100, 200),
                    'reg_hol_ot'            => rand(100, 200),
                    'reg_hol_ot_nd'         => rand(100, 200),
                    'reg_hol_rest_hrs'      => rand(100, 200),
                    'reg_hol_rest_nd'       => rand(100, 200),
                    'reg_hol_rest_ot'       => rand(100, 200),
                    'reg_hol_rest_ot_nd'    => rand(100, 200),
                    'spe_hol_hrs'           => rand(100, 200),
                    'spe_hol_nd'            => rand(100, 200),
                    'spe_hol_ot'            => rand(100, 200),
                    'spe_hol_ot_nd'         => rand(100, 200),
                    'spe_hol_rest_hrs'      => rand(100, 200),
                    'spe_hol_rest_nd'       => rand(100, 200),
                    'spe_hol_rest_ot'       => rand(100, 200),
                    'spe_hol_rest_ot_nd'    => rand(100, 200),
                    'abs_hours'             => rand(1, 5),
                    'ut_hours'              => rand(10, 20),
                    'td_hours'              => rand(10, 20),
                    'created_at'            => Carbon::parse($payroll->cut_off_end),
                    'updated_at'            => Carbon::parse($payroll->cut_off_end),
                ]);
            });
        });

        DB::table('payroll_summaries')->insert($mockData);
    }
}
