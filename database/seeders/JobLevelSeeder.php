<?php

namespace Database\Seeders;

use App\Models\JobLevel;
use Illuminate\Database\Seeder;

class JobLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobLevels = collect([
            [1, 'Junior Associate'],
            [2, 'Associate'],
            [3, 'Senior Associate'],
            [4, 'Manager'],
            [5, 'Senior Manager'],
        ]);

        $jobLevels->eachSpread(function (int $level, string $name) {
            JobLevel::create([
                'job_level_id' => $level,
                'job_level' => $level,
                'job_level_name' => $name,
            ]);
        });
    }
}
