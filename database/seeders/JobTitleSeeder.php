<?php

namespace Database\Seeders;

use App\Enums\JobQualificationPriorityLevel;
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

        activity()->withoutLogs(function () use ($jobTitles) {
            $jobTitles->each(function ($item) {
                $skills = implode("\n", $item['skill']);
                $experiences = implode("\n", $item['experience']);

                $title = JobTitle::create([
                    'job_title' => $item['title'],
                    'job_desc' => "\nSkills:\n" . $skills . "\nExperiences:\n" . $experiences,
                    'department_id' => $item['department'],
                    'job_level_id' => $item['level'],
                    'job_family_id' => $item['family'],
                ]);

                array_map(function ($skill) use ($title) {
                    $title->skills()->create([
                        'job_title_id' => $title->job_title_id,
                        'keyword' => $skill,
                        'priority' => fake()->randomElement(array_map(fn ($cases) => $cases->value,
                            JobQualificationPriorityLevel::cases())),
                    ]);
                }, $item['skill']);

                array_map(function ($education) use ($title) {
                    $title->educations()->create([
                        'job_title_id' => $title->job_title_id,
                        'keyword' => $education,
                        'priority' => fake()->randomElement(array_map(fn ($cases) => $cases->value,
                            JobQualificationPriorityLevel::cases())),
                    ]);
                }, $item['education']);

                array_map(function ($experience) use ($title) {
                    $title->experiences()->create([
                        'job_title_id' => $title->job_title_id,
                        'keyword' => $experience,
                        'priority' => fake()->randomElement(array_map(fn ($cases) => $cases->value,
                            JobQualificationPriorityLevel::cases())),
                    ]);
                }, $item['experience']);
            });
        });

    }
}
