<?php

namespace Database\Seeders;

use App\Models\PerformancePeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class PerformancePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods = ['third month', 'fifth month', 'final month', 'annual'];

        Arr::map($periods, function (string $period) {
            PerformancePeriod::create([
                'perf_period_name' => $period,
            ]);
        });
    }
}
