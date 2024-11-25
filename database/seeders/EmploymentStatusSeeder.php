<?php

namespace Database\Seeders;

use App\Models\EmploymentStatus;
use Illuminate\Database\Seeder;

class EmploymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = collect([
            'Probationary',
            'Regular',
            'Resigned',
            'Retired',
            'Terminated',
        ]);

        $statuses->each(function (string $status) {
            EmploymentStatus::create([
                'emp_status_name' => $status,
                'emp_status_desc' => fake()->paragraph(),
            ]);
        });
    }
}
