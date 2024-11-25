<?php

namespace Database\Seeders;

use App\Models\JobTitle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class JobTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobTitles = collect(Storage::json('public/utils/job-titles.json'));

        $jobTitles->each(function ($item) {
            JobTitle::create([
                'job_title' => $item['title'],
                'job_desc' => fake()->boolean ? fake()->paragraph() : fake()->paragraph(500),
                'department_id' => $item['department'],
                'job_level_id' => $item['level'],
                'job_family_id' => $item['family'],
            ]);
        });
    }
}
