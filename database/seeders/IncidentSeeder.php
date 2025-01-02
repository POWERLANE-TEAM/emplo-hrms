<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Incident;
use App\Models\IssueType;
use Illuminate\Database\Seeder;

class IncidentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        activity()->withoutLogs(function () {
            $incidents = Incident::factory(20)->create();

            $types = IssueType::all();

            $incidents->each(function ($item) use ($types) {
                $randomTypes = $types->random(rand(1, 3));
                $item->types()->attach($randomTypes->pluck('issue_type_id')->toArray());
                $item->collaborators()->attach(Employee::inRandomOrder()->first()->employee_id, [
                    'is_editor' => true,
                ]);
            });            
        });
    }
}
