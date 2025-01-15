<?php

namespace App\Livewire\Admin\JobBoard;

use App\Enums\UserPermission;
use App\Models\JobTitle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateJobListing extends Component
{
    #[Validate]
    public $state = [
        'selectedJob' => null,
        'vacancyCount' => 0,
        'applicationDeadline' => null,
    ];

    public $isJobSelected = false;

    public $jobDetails;

    public $feedback = [
        'success' => false,
        'message' => '',
    ];

    public function updated($property)
    {
        if ($property === 'state.selectedJob') {
            $this->isJobSelected = true;
            $this->renderSelectedJob();
        }
    }

    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_JOB_LISTING)) {
            $this->reset();

            abort(403);
        }

        $this->validate();

        $jobTitle = JobTitle::find($this->state['selectedJob']);

        if (! $jobTitle) {
            $this->feedback = [
                'message' => __('Something went wrong.'),
            ];
        } else {
            DB::transaction(function () use ($jobTitle) {
                $jobTitle->vacancies()->create([
                    'vacancy_count' => $this->state['vacancyCount'],
                    'application_deadline_at' => $this->state['applicationDeadline'],
                ]);
            });
            $this->feedback = [
                'success' => true,
                'message' => __('New job listing was added successfully.'),
            ];
        }
        $this->dispatch('changes-saved', $this->feedback);

        $this->reset();
        $this->resetErrorBag();
    }

    public function rules()
    {
        return [
            'state.selectedJob' => 'required|integer|exists:job_titles,job_title_id',
            'state.vacancyCount' => 'required|integer|min:1',
            'state.applicationDeadline' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'state.selectedJob' => __('Please select a job.'),
            'state.vacancyCount' => __('Vacancy count should be more than one (1)'),
            // 'state.applicationDeadline' => __('Hello')
        ];
    }

    public function renderSelectedJob()
    {
        $this->jobDetails = JobTitle::where('job_title_id', $this->state['selectedJob'])
            ->with([
                'department', 
                'skills',
                'educations',
                'experiences',
                'jobFamily', 
                'jobLevel'
            ])
            ->get()
            ->map(function ($job) {
                return (object) [
                    'title'         => $job->job_title,
                    'description'   => $job->job_title_desc,
                    'department'    => $job->department->department_name,
                    'family'        => $job->jobFamily->job_family_name,
                    'level'         => $job->jobLevel->job_level,
                    'levelName'     => $job->jobLevel->job_level_name,
                    'skills'        => $job->skills,
                    'educations'    => $job->educations,
                    'experiences'   => $job->experiences,
                ];
            });
    }

    #[Computed]
    public function jobTitles()
    {
        return JobTitle::all()->map(function ($item) {
            return (object) [
                'id' => $item->job_title_id,
                'title' => $item->job_title,
                'description' => $item->job_title_desc,
            ];
        });
    }

    public function render()
    {
        return view('livewire.admin.job-board.create-job-listing');
    }
}
