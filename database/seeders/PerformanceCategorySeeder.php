<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\PerformanceCategory;
use Illuminate\Support\Facades\Storage;

class PerformanceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = collect(Storage::json('public/utils/performance-categories.json'));

        $categories->each(function ($category) {
            PerformanceCategory::create([
                'perf_category_name' => Str::lower($category['name']),
                'perf_category_desc' => Str::lower($category['description']),
            ]);
        });
    }
}