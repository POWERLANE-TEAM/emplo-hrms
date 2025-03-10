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
                    'leave_category_name' => $item->name,
                    'leave_category_desc' => $item->description,
                    'allotted_days' => $item?->days_allowed ?? null,
                    'is_proof_required' => $item->is_proof_required ?? false,
                ]);
            });
        });
    }
}
