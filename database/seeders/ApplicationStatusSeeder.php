<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus as EnumsApplicationStatus;
use App\Models\ApplicationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application_statuses = [
            ['application_status_name' => EnumsApplicationStatus::PENDING, 'application_status_desc' => EnumsApplicationStatus::PENDING->label()],
            ['application_status_name' => EnumsApplicationStatus::APPROVED, 'application_status_desc' => EnumsApplicationStatus::APPROVED->label()],
            ['application_status_name' => EnumsApplicationStatus::REJECTED, 'application_status_desc' => EnumsApplicationStatus::REJECTED->label()],
        ];

        foreach ($application_statuses as $application_status) {
            ApplicationStatus::create($application_status);
        }
    }
}
