<?php

namespace Database\Factories;

use App\Models\Applicant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicantSkill>
 */
class ApplicantSkillFactory extends Factory
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
            'applicant_id' => Applicant::inRandomOrder()->first()->applicant_id,
            'skill' => fake()->randomElement($keywords),
        ];
    }
}
