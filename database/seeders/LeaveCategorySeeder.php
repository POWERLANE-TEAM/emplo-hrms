<?php

namespace Database\Seeders;

use App\Models\LeaveCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class LeaveCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaves = collect(Storage::json('public/utils/leave-categories.json'))
            ->map(fn ($leave) => (object) $leave);

        activity()->withoutLogs(function () use ($leaves) {
            $leaves->each(function ($item) {
                LeaveCategory::create([
                    'leave_name' => $item->name,
                    'leave_desc' => $item->description,
                ]);
            });
        });
    }
}
