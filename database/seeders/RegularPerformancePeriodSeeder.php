<?php

namespace Database\Seeders;

use App\Models\RegularPerformancePeriod;
use Illuminate\Database\Seeder;

class RegularPerformancePeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RegularPerformancePeriod::factory()->create();
    }
}
