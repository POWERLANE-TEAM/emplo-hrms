<?php

namespace Database\Seeders;

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
            ['application_status_name' => 'PENDING', 'application_status_desc' => 'Pending approval'],
            ['application_status_name' => 'APPROVED', 'application_status_desc' => 'Approved application'],
            ['application_status_name' => 'REJECTED', 'application_status_desc' => 'Rejected application'],
        ];

        foreach ($application_statuses as $application_status) {
            ApplicationStatus::create($application_status);
        }
    }
}
