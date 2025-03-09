<?php

namespace Database\Seeders;

use App\Models\JobFamily;
use Illuminate\Database\Seeder;

class JobFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobFamilies = collect([
            [1, 'Operations'],
            [2, 'Accounting'],
            [3, 'Administrative'],
            [4, 'General Affairs-Support'],
            [5, 'Human Resources-Operations'],
            [6, 'General Affairs'],
            [7, 'Payroll'],
        ]);

        activity()->withoutLogs(function () use ($jobFamilies) {
            JobFamily::unguard();
            $jobFamilies->eachSpread(function (int $id, string $name) {
                JobFamily::create([
                    'job_family_id' => $id,
                    'job_family_name' => $name,
                    'job_family_desc' => fake()->paragraph(),
                    'office_head' => null,
                    'supervisor' => null,
                ]);
            });
            JobFamily::unguard();
        });
    }
}
