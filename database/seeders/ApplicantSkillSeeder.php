<?php

namespace Database\Seeders;

use App\Models\ApplicantSkill;
use Illuminate\Database\Seeder;

class ApplicantSkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicantSkill::factory(300)->create();
    }
}
