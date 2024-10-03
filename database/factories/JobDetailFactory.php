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
            'job_title_id' => JobTitle::factory(),
            'job_level_id' => JobLevel::factory(),
            'job_family_id' => JobFamily::factory(),
            'area_id' => SpecificArea::factory(),
        ];
    }
}
