<?php

namespace Database\Seeders;

use App\Enums\InterviewRating as EnumsInterviewRating;
use App\Models\InterviewRating;
use Illuminate\Database\Seeder;

class InterviewRatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        InterviewRating::truncate();

        foreach (EnumsInterviewRating::cases() as $status) {
            InterviewRating::create([
                'rating_code' => $status->value,
                'rating_desc' => strtolower($status->label()),
            ]);
        }
    }
}
