<?php

namespace Database\Seeders;

use App\Models\JobSkillKeyword;
use Illuminate\Database\Seeder;

class JobSkillKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobSkillKeyword::factory(50)->create();
    }
}
