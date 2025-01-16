<?php

namespace Database\Seeders;

use App\Models\InterviewParameter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InterviewParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parameters = [
            ['parameter_desc' => 'Specific skills proficiency or technical expertise.'],
            ['parameter_desc' => 'Ability to think strategically.'],
            ['parameter_desc' => 'Domain knowledge or strong understanding of the company/industry.'],
            ['parameter_desc' => 'Leadership potential and qualities.'],
            ['parameter_desc' => 'Teamwork dynamics or ability to work effectively within a team.'],
            ['parameter_desc' => 'Values and work style align with the company culture.'],
            ['parameter_desc' => 'Long-term career goals and aspirations to see if align with the company\'s growth plans.'],
            ['parameter_desc' => 'Salary expectations.'],
            ['parameter_desc' => 'Chemistry and fit or personality and how well he/she would fit within the existing team dynamic.'],
        ];

        foreach ($parameters as $parameter) {
            InterviewParameter::create($parameter);
        }
    }
}
