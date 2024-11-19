<?php

namespace App\Livewire\Info;

use Livewire\Component;

class ShowJobInfo extends Component
{
    public $selectedJobId = null; // Initialize to null
    public $items = [ // Placeholder data
        '1' => [
            'job_position' => 'Accountant',
            'dept' => 'Accounting Department',
            'job_desc' => 'Manage financial records and oversee budget planning',
            'Qualifications' => [
                'Bachelor\'s in Accounting',
                'CPA Certification',
                '3+ years of experience'
            ]
        ],
        '2' => [
            'job_position' => 'HR Manager',
            'dept' => 'Human Resources',
            'job_desc' => 'Handle recruitment, onboarding, and employee relations',
            'Qualifications' => [
                'Bachelor\'s in Human Resources',
                'Excellent communication skills',
                'Knowledge of labor laws'
            ]
        ],
        '3' => [
            'job_position' => 'Marketer',
            'dept' => 'Marketing',
            'job_desc' => 'Develop and execute marketing strategies to increase brand awareness',
            'Qualifications' => [
                'Degree in Marketing or related field',
                'Creativity and strategic thinking',
                'Proficiency in digital marketing tools'
            ]
        ],
        '4' => [
            'job_position' => 'Data Analyst',
            'dept' => 'IT Department',
            'job_desc' => 'Maintain company’s IT infrastructure and support systems',
            'Qualifications' => [
                'Bachelor\'s in Computer Science',
                'Experience with network security',
                'Problem-solving skills'
            ]
        ],
    ];

    public function updateJobPosition($jobId)
    {
        $this->selectedJobId = $jobId;
        logger()->info('Selected Job ID updated:', ['selectedJobId' => $this->selectedJobId]);
    }

    public function render()
    {
        return view('livewire.info.show-job-info', [
            'selectedJob' => $this->items[$this->selectedJobId] ?? null,
        ]);
    }
}
