<?php

namespace App\Livewire\Admin\JobTitle;

use Livewire\Component;
use App\Models\JobLevel;
use App\Models\JobTitle;
use App\Models\JobFamily;
use App\Models\Department;
use Livewire\Attributes\On;
use App\Enums\UserPermission;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\JobTitle\SetQualifications;

class CreateJobTitleForm extends Component
{
    #[Validate]
    public $state = [
        'department' => null,
        'family' => null,
        'level' => null,
        'title' => '',
        'description' => null,
        'baseSalary' => null,
    ];

    public $qualifications = [];

    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_JOB_TITLE)) {
            $this->reset();

            abort(403);
        }

        $this->validate();

        DB::transaction(function () {
            $jobTitle = JobTitle::create([
                'job_title' => $this->state['title'],
                'job_desc' => $this->state['description'],
                'base_salary' => $this->state['baseSalary'],
                'department_id' => $this->state['department'],
                'job_level_id' => $this->state['level'],
                'job_family_id' => $this->state['family'],
            ]);

            collect($this->qualifications)->each(function ($qualification) use ($jobTitle) {
                $jobTitle->qualifications()->create([
                    'job_title_qual_desc' => $qualification['description'],
                    'job_title_id' => $jobTitle->job_title_id,
                    'priority_level' => $qualification['priorityLevel'],                
                ]);
            });
        });
        
        $this->dispatch('job-title-created')->to(SetQualifications::class);

        $this->reset();
    }

    #[On('qualification-added')]
    public function addQualification($qualification, $priority)
    {
        $this->qualifications[] = [
            'description' => $qualification,
            'priorityLevel' => $priority,
        ];
    }

    #[On('qualification-updated')]
    public function updateQualification($index, $qualification, $priority)
    {
        $this->qualifications[$index]['description'] = $qualification;
        $this->qualifications[$index]['priorityLevel'] = $priority;
    }

    public function rules()
    {
        return [
            'state.department' => 'required',
            'state.family' => 'required',
            'state.level' => 'required',
            'state.title' => 'required',
            'state.description' => 'nullable',
            'state.baseSalary' => 'nullable|numeric|min:5'
        ];
    }

    public function messages()
    {
        return [
            'state.department' => __('Department is required.'),
            'state.family' => __('Job family is required.'),
            'state.level' => __('Job level is required.'),
            'state.title' => __('Job title is required.'),
            'state.description' => __('Whatever.'),
            'state.baseSalary' => __('Whatever.'),
        ];
    }

    #[Computed]
    public function departments()
    {
        return Department::all()->pluck('department_name', 'department_id')->toArray();   
    }

    #[Computed]
    public function jobLevels()
    {
        return JobLevel::all()->pluck('job_level_name', 'job_level_id')->toArray();
    }

    #[Computed]
    public function jobFamilies()
    {
        return JobFamily::all()->pluck('job_family_name', 'job_family_id')->toArray();
    }

    public function render()
    {
        return view('livewire.admin.job-title.create-job-title-form');
    }
}
