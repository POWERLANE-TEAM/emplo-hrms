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

        $jobTitles->each(function ($jobTitle) {
            JobTitle::create([
                'job_title' => $jobTitle['title'],
                'job_desc' => fake()->paragraph(),
                'department_id' => $jobTitle['department'],
            ]);
        });
    }
}
