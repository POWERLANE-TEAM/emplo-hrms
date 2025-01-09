<?php

namespace Database\Seeders;

use App\Models\PerformanceRating;
use Illuminate\Database\Seeder;

class PerformanceRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ratings = [
            [1, 'needs improvement'],
            [2, 'meets expectations'],
            [3, 'exceeds expectations'],
            [4, 'outstanding'],
        ];

        collect($ratings)->eachSpread(function (int $rating, string $description) {
            PerformanceRating::create([
                'perf_rating' => $rating,
                'perf_rating_name' => $description,
            ]);
        });
    }
}
