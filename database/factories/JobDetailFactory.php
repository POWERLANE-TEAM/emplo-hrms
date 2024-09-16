<?php

namespace Database\Factories;

use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\SpecificArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JobDetail>
 */
class JobDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_title_id' => JobTitle::inRandomOrder()->first()->job_title_id ?? 1,
            'job_level_id' => JobLevel::inRandomOrder()->first()->job_level_id ?? 1,
            'job_family_id' => JobFamily::inRandomOrder()->first()->job_family_id ?? 1,
            'area_id' => SpecificArea::inRandomOrder()->first()->area_id ?? 1,
        ];
    }
}
