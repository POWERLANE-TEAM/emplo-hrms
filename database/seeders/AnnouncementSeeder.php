<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\JobFamily;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $announcements = Announcement::factory(10)->create();
            $jobFamilies = JobFamily::all();

            $announcements->each(function ($item) use ($jobFamilies) {
                $item->offices()->attach($jobFamilies->random(rand(2, 7)));
            });
        });
    }
}
