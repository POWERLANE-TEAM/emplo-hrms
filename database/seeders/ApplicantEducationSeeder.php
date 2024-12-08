<?php

namespace Database\Seeders;

use App\Models\ApplicantEducation;
use Illuminate\Database\Seeder;

class ApplicantEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicantEducation::factory(300)->create();
    }
}
