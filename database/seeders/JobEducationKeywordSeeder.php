<?php

namespace Database\Seeders;

use App\Models\JobEducationKeyword;
use Illuminate\Database\Seeder;

class JobEducationKeywordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobEducationKeyword::factory(20)->create();
    }
}
