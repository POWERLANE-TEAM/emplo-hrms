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
            'Operations',
            'Accounting',
            'Administrative',
            'General Affairs-Support',
            'Human Resources-Operations',
            'General Affairs',
            'Payroll',
        ]);

        $jobFamilies->each(function (string $name) {
            JobFamily::create([
                'job_family_name' => $name,
                'job_family_desc' => fake()->paragraph(),
                'office_head' => null,
            ]);
        });
    }
}
