<?php

namespace Database\Seeders;

use App\Models\Overtime;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OvertimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $overtimes = Overtime::factory(40)->create();

            foreach ($overtimes as $overtime) {
                $overtime->processes()->create([
                    'processable_type' => Overtime::class,
                    'processable_id' => $overtime->overtime_id,
                ]);
            }
        });
    }
}