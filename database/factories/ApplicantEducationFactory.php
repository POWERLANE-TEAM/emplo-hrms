<?php

namespace Database\Factories;

use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ApplicantEducation>
 */
class ApplicantEducationFactory extends Factory
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
            'applicant_id' => Applicant::inRandomOrder()->first()->applicant_id,
            'education' =>  fake()->randomElement($keywords),
        ];
    }
}
