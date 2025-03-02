<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employeeShifts = collect(Storage::json('public/utils/employee-shifts.json'));

        $shiftData = [];

        $shifts = Shift::all();

        $employeeShifts->each(function ($item) use (&$shiftData, $shifts) {
            array_push($shiftData, [
                'shift_id' => $shifts->firstWhere('shift_name', $item['shift'])->shift_id,
                'start_time' => Carbon::parse($item['start_time'])->toTimeString(),
                'end_time' => Carbon::parse($item['end_time'])->toTimeString(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        DB::table('employee_shifts')->insert($shiftData);
    }
}
