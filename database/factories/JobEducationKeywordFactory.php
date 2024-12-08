<?php

namespace Database\Factories;

use App\Models\JobTitle;
use Illuminate\Support\Facades\Storage;
use App\Enums\JobQualificationPriorityLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobEducationKeyword>
 */
class JobEducationKeywordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $educations = Storage::json('public/utils/job-titles.json');

        $keywords = array_map(function ($item) {
            return $item['education'];
        }, $educations);

        $keywords = array_merge(...$keywords);
        
        return [
            'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id,
            'keyword' => fake()->randomElement($keywords),
            'priority' => fake()->randomElement(array_map(fn ($case) => $case->value, JobQualificationPriorityLevel::cases())),
        ];
    }
}
