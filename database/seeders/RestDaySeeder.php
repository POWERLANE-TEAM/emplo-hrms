<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RestDaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $restDays = [];

        Employee::all()
            ->each(function ($employee) use (&$restDays) {
                array_push($restDays, [
                    'employee_id' => $employee->employee_id,
                    'day' => array_rand(Carbon::getDays()),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        DB::table('rest_days')->insert($restDays);
    }
}
