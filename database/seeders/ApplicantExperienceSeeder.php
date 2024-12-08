<?php

namespace Database\Seeders;

use App\Models\ApplicantExperience;
use Illuminate\Database\Seeder;

class ApplicantExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicantExperience::factory(300)->create();
    }
}
