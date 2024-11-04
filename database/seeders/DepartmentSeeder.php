<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = collect([
            [
                'id' => 1,
                'name' => 'Area',
                'function' => 'Focus on specific area/branch',
            ],
            [
                'id' => 2,
                'name' => 'Corporate',
                'function' => 'Cover overall operations',
            ],
        ]);
        
        $departments->each(function ($department) {
            Department::create([
                'department_id' => $department['id'],
                'department_name' => $department['name'],
                'department_function' => $department['function']
            ]);
        });
    }
}
