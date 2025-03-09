<?php

namespace Database\Factories;

use App\Enums\JobQualificationPriorityLevel;
use App\Models\JobTitle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobSkillKeyword>
 */
class JobSkillKeywordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skills = Storage::json('public/utils/job-titles.json');

        $keywords = array_map(function ($item) {
            return $item['skill'];
        }, $skills);

        $keywords = array_merge(...$keywords);

        return [
            'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id,
            'keyword' => fake()->randomElement($keywords),
            'priority' => fake()->randomElement(array_map(fn ($case) => $case->value, JobQualificationPriorityLevel::cases())),
        ];
    }
}
