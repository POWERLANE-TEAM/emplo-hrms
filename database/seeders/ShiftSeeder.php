<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = collect([
            [
                'shift_name' => 'Regular',
                'start_time' => '06:00:00',  // 6 am
                'end_time' => '22:00:00',   // 10 pm
            ],
            [
                'shift_name' => 'Night Differential',
                'start_time' => '22:00:00', // 10 pm
                'end_time' => '06:00:00',   // 6 am
            ],
        ]);

        $shifts->each(function ($shift) {
            Shift::create([
                'shift_name' => $shift['shift_name'],
                'start_time' => $shift['start_time'],
                'end_time' => $shift['end_time'],
            ]);
        });
    }
}
