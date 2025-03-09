<?php

namespace Database\Seeders;

use App\Enums\IssueConfidentiality;
use App\Enums\IssueStatus;
use App\Models\Employee;
use App\Models\Issue;
use App\Models\IssueType;
use Illuminate\Database\Seeder;

class Issue2024Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $issues = Issue::factory(50)->create()->each(function ($issue) {
            $filedAt = now()->subMonths(rand(0, 11))->startOfMonth()->addDays(rand(0, 28));
            $statusMarkedAt = $filedAt->copy()->addDays(rand(1, 30));
            $modifiedAt = $statusMarkedAt->copy()->addDays(rand(1, 30));

            $issue->filed_at = $filedAt;
            $issue->status_marked_at = $statusMarkedAt;
            $issue->modified_at = $modifiedAt;

            $issue->issue_reporter = Employee::inRandomOrder()->first()->employee_id;
            $issue->confidentiality = fake()->randomElement(array_map(fn ($case) => $case->value, IssueConfidentiality::cases()));
            $issue->occured_at = fake()->dateTimeBetween('-2 years', '-1 year');
            $issue->issue_description = fake()->paragraph();
            $issue->desired_resolution = fake()->paragraph();
            $issue->status = IssueStatus::RESOLVED->value;

            $issue->save();
        });

        $issueTypes = IssueType::all();

        $issues->each(function ($item) use ($issueTypes) {
            $randomTypes = $issueTypes->random(rand(1, 3));
            $item->types()->attach($randomTypes->pluck('issue_type_id')->toArray());
        });
    }
}
