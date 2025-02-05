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
                    'reg_hrs'               => fake()->randomFloat(2, 100, 200),
                    'reg_nd'                => fake()->randomFloat(2, 100, 200),
                    'reg_ot'                => fake()->randomFloat(2, 100, 200),
                    'reg_ot_nd'             => fake()->randomFloat(2, 100, 200),
                    'rest_hrs'              => fake()->randomFloat(2, 100, 200),
                    'rest_nd'               => fake()->randomFloat(2, 100, 200),
                    'rest_ot'               => fake()->randomfloat(2, 100, 200),
                    'rest_ot_nd'            => fake()->randomfloat(2, 100, 200),
                    'reg_hol_hrs'           => fake()->randomfloat(2, 100, 200),
                    'reg_hol_nd'            => fake()->randomfloat(2, 100, 200),
                    'reg_hol_ot'            => fake()->randomfloat(2, 100, 200),
                    'reg_hol_ot_nd'         => fake()->randomfloat(2, 100, 200),
                    'reg_hol_rest_hrs'      => fake()->randomfloat(2, 100, 200),
                    'reg_hol_rest_nd'       => fake()->randomfloat(2, 100, 200),
                    'reg_hol_rest_ot'       => fake()->randomfloat(2, 100, 200),
                    'reg_hol_rest_ot_nd'    => fake()->randomfloat(2, 100, 200),
                    'spe_hol_hrs'           => fake()->randomfloat(2, 100, 200),
                    'spe_hol_nd'            => fake()->randomfloat(2, 100, 200),
                    'spe_hol_ot'            => fake()->randomfloat(2, 100, 200),
                    'spe_hol_ot_nd'         => fake()->randomfloat(2, 100, 200),
                    'spe_hol_rest_hrs'      => fake()->randomfloat(2, 100, 200),
                    'spe_hol_rest_nd'       => fake()->randomfloat(2, 100, 200),
                    'spe_hol_rest_ot'       => fake()->randomfloat(2, 100, 200),
                    'spe_hol_rest_ot_nd'    => fake()->randomfloat(2, 100, 200),
                    'abs_days'              => fake()->numberBetween(1, 15),
                    'ut_hours'              => fake()->randomFloat(2, 10, 20),
                    'td_hours'              => fake()->randomFloat(2, 10, 20),
                    'created_at'            => Carbon::parse($payroll->cut_off_end),
                    'updated_at'            => Carbon::parse($payroll->cut_off_end),
                ]);
            });
        });

        DB::table('payroll_summaries')->insert($mockData);
    }
}
