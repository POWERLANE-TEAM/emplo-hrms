<?php

namespace Database\Seeders;

use App\Models\Issue;
use App\Models\IssueType;
use Illuminate\Database\Seeder;

class IssueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $issues = Issue::factory(20)->create();
        $issueTypes = IssueType::all();

        $issues->each(function ($item) use ($issueTypes) {
            $randomTypes = $issueTypes->random(rand(1, 3));
            $item->types()->attach($randomTypes->pluck('issue_type_id')->toArray());
        });
    }
}
