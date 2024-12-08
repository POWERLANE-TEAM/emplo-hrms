<?php

namespace Database\Seeders;

use App\Models\JobExperienceKeyword;
use Illuminate\Database\Seeder;

class JobExperienceKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobExperienceKeyword::factory(50)->create();
    }
}
