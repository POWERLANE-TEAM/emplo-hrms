<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $holidays = Storage::json('public/utils/ph-holidays.json');

        activity()->withoutLogs(function () use ($holidays) {
            collect($holidays)->each(function ($item) {
                Holiday::create([
                    'event' => $item['event'],
                    'date' => $item['date'],
                    'type' => $item['type'],
                ]);
            });
        });
    }
}
