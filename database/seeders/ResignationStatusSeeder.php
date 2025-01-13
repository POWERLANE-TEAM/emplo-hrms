<?php

namespace Database\Seeders;

use App\Enums\ResignationStatus as EnumsResignationStatus;
use App\Models\ResignationStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResignationStatusSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ResignationStatus::truncate();

        foreach (EnumsResignationStatus::cases() as $status) {
            ResignationStatus::create([
                'resignation_status_id' => $status->value,
                'resignation_status_name' => strtolower($status->label()),
            ]);
        }
    }
}
