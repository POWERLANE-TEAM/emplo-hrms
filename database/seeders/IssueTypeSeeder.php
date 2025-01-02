<?php

namespace Database\Seeders;

use App\Models\IssueType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IssueTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            'workplace harassment',
            'workload and resources',
            'policy violation',
            'safety concerns',
            'salary and benefits discrepancies',
            'administrative errors',
            'ethical concerns',
            'unfair treatment',
            'workplace harassment',
            'performance appraisal disputes',
            'interpersonal conflicts',
            'equipment malfunction',
        ];

        collect($defaults)->each(function (string $item) {
            IssueType::create([
                'issue_type_name' => $item,
            ]);
        });
    }
}
