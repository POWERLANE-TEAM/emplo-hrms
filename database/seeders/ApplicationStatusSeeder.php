<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus as EnumsApplicationStatus;
use App\Models\ApplicationStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;


class ApplicationStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $application_statuses = [
            ['application_status_name' => Str::lower(EnumsApplicationStatus::PENDING->label()), 'application_status_desc' => ''],
            ['application_status_name' => Str::lower(EnumsApplicationStatus::ASSESSMENT_SCHEDULED->label()), 'application_status_desc' => ''],
            ['application_status_name' => Str::lower(EnumsApplicationStatus::PRE_EMPLOYED->label()), 'application_status_desc' => ''],
            ['application_status_name' => Str::lower(EnumsApplicationStatus::APPROVED->label()), 'application_status_desc' => ''],
            ['application_status_name' => Str::lower(EnumsApplicationStatus::REJECTED->label()), 'application_status_desc' => ''],
        ];

        foreach ($application_statuses as $application_status) {
            ApplicationStatus::create($application_status);
        }
    }
}
