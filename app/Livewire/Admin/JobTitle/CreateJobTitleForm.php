<?php

namespace App\Livewire\Admin\JobTitle;

use App\Enums\UserPermission;
use App\Models\Department;
use App\Models\JobFamily;
use App\Models\JobLevel;
use App\Models\JobTitle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

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

    public $educationQualifications = [];

    public $skillQualifications = [];

    public $expQualifications = [];

    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_JOB_TITLE)) {
            $this->reset();

            abort(403);
        }

        $this->validate();

        DB::transaction(function () {
            $jobTitle = JobTitle::create([
                'job_title'     => $this->state['title'],
                'job_desc'      => $this->state['description'],
                'base_salary'   => $this->state['baseSalary'],
                'department_id' => $this->state['department'],
                'job_level_id'  => $this->state['level'],
                'job_family_id' => $this->state['family'],
            ]);

            collect($this->educationQualifications)->each(function ($education) use ($jobTitle) {
                $jobTitle->educations()->create([
                    'keyword' => $education['description'],
                    'priority' => $education['priorityLevel'],
                ]);
            });

            collect($this->skillQualifications)->each(function ($skill) use ($jobTitle) {
                $jobTitle->skills()->create([
                    'keyword' => $skill['description'],
                    'priority' => $skill['priorityLevel'],
                ]);
            });

            collect($this->expQualifications)->each(function ($exp) use ($jobTitle) {
                $jobTitle->experiences()->create([
                    'keyword' => $exp['description'],
                    'priority' => $exp['priorityLevel'],
                ]);
            });
        });

        $this->dispatch('createdJobTitle');

        $this->reset();
    }

    #[On('addedEducationQualification')]
    public function addEducationQualification($qualification, $priority)
    {
        $this->educationQualifications[] = [
            'description' => $qualification,
            'priorityLevel' => $priority,
        ];
    }

    #[On('updatedEducationQualification')]
    public function updateEducationQualification($index, $qualification, $priority)
    {
        $this->educationQualifications[$index]['description'] = $qualification;
        $this->educationQualifications[$index]['priorityLevel'] = $priority;
    }

    #[On('addedSkillQualification')]
    public function addSkillQualification($qualification, $priority)
    {
        $this->skillQualifications[] = [
            'description' => $qualification,
            'priorityLevel' => $priority,
        ];
    }

    #[On('updatedSkillQualification')]
    public function updateSkillQualification($index, $qualification, $priority)
    {
        $this->skillQualifications[$index]['description'] = $qualification;
        $this->skillQualifications[$index]['priorityLevel'] = $priority;
    }

    #[On('addedExpQualification')]
    public function addExpQualification($qualification, $priority)
    {
        $this->expQualifications[] = [
            'description' => $qualification,
            'priorityLevel' => $priority,
        ];
    }

    #[On('updatedExpQualification')]
    public function updateExpQualification($index, $qualification, $priority)
    {
        $this->expQualifications[$index]['description'] = $qualification;
        $this->expQualifications[$index]['priorityLevel'] = $priority;
    }

    #[On('removeEducationQualification')]
    public function removeEducationQualification(int $index)
    {
        unset($this->educationQualifications[$index]);
    }

    #[On('removeSkillQualification')]
    public function removeSkillQualification(int $index)
    {
        unset($this->skillQualifications[$index]);
    }

    #[On('removeExpQualification')]
    public function removeExpQualification(int $index)
    {
        unset($this->expQualifications[$index]);
    }

    public function rules()
    {
        return [
            'state.department'  => 'required',
            'state.family'      => 'required',
            'state.level'       => 'required',
            'state.title'       => 'required|string|max:255',
            'state.description' => 'nullable|string|max:500',
            'state.baseSalary'  => 'nullable|numeric|min:5',
        ];
    }

    public function messages()
    {
        return [
            'state.department'      => __('Department is required.'),
            'state.family'          => __('Job family is required.'),
            'state.level'           => __('Job level is required.'),
            'state.title.required'  => __('Job title is required.'),
            'state.description.max' => __('Description only allows for maximum of 500 characters.'),
            'state.baseSalary'      => __('Numeric values only.'),
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
