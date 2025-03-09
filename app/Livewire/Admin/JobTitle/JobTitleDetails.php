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
use Livewire\Component;

class JobTitleDetails extends Component
{
    public JobTitle $jobTitle;

    public $department;

    public $family;

    public $level;

    public $title;

    public $description;

    public $baseSalary;

    public $educationQualifications = [];

    public $skillQualifications = [];

    public $expQualifications = [];

    public function mount()
    {
        $this->department = $this->jobTitle->department->department_id;
        $this->family = $this->jobTitle->jobFamily->job_family_id;
        $this->level = $this->jobTitle->jobLevel->job_level_id;
        $this->title = $this->jobTitle->job_title;
        $this->description = $this->jobTitle->job_desc;
        $this->baseSalary = $this->jobTitle?->base_salary;
    }

    public function update()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_JOB_TITLE)) {
            $this->reset();

            abort(403);
        }

        $this->validate();

        DB::transaction(function () {
            $this->jobTitle->update([
                'job_title' => $this->title,
                'job_desc' => $this->description,
                'base_salary' => $this->baseSalary,
                'department_id' => $this->department,
                'job_level_id' => $this->level,
                'job_family_id' => $this->family,
            ]);

            collect($this->educationQualifications)->each(function ($education) {
                $this->jobTitle->educations()->create([
                    'keyword' => $education['description'],
                    'priority' => $education['priorityLevel'],
                ]);
            });

            collect($this->skillQualifications)->each(function ($skill) {
                $this->jobTitle->skills()->create([
                    'keyword' => $skill['description'],
                    'priority' => $skill['priorityLevel'],
                ]);
            });

            collect($this->expQualifications)->each(function ($exp) {
                $this->jobTitle->experiences()->create([
                    'keyword' => $exp['description'],
                    'priority' => $exp['priorityLevel'],
                ]);
            });
        });

        $this->dispatch('updatedJobTitle');
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
            'department' => 'required',
            'family' => 'required',
            'level' => 'required',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'baseSalary' => 'nullable|numeric|min:5',
        ];
    }

    public function messages()
    {
        return [
            'department' => __('Department is required.'),
            'family' => __('Job family is required.'),
            'level' => __('Job level is required.'),
            'title.required' => __('Job title is required.'),
            'description.max' => __('Description only allows for maximum of 500 characters.'),
            'baseSalary' => __('Numeric values only.'),
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
        return view('livewire.admin.job-title.job-title-details');
    }
}
