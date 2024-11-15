<?php

namespace App\Livewire\Admin\JobTitle;

use Livewire\Component;
use App\Models\JobTitle;
use App\Models\Department;
use Livewire\Attributes\On;
use App\Enums\UserPermission;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateJobTitleForm extends Component
{
    #[Validate('required')]
    public $department;

    #[Validate('required')]
    public $jobTitleName;

    #[Validate('nullable')]
    public $jobTitleDesc;

    public $jobQualifications = [];

    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_JOB_TITLE)) {
            $this->reset();

            abort(403);
        }

        $this->validate();

        DB::transaction(function () {
            $jobTitle = JobTitle::create([
                'job_title' => $this->jobTitleName,
                'job_desc' => $this->jobTitleDesc,
                'department_id' => $this->department,
            ]);

            collect($this->jobQualifications)->each(function ($jobQualification) use ($jobTitle) {
                $jobTitle->qualifications()->create([
                    'job_title_qual_desc' => $jobQualification['description'],
                    'job_title_id' => $jobTitle->job_title_id,
                    'priority_level' => $jobQualification['priorityLevel'],                
                ]);
            });
        });
        
        $this->dispatch('job-title-created');

        $this->reset();
    }

    #[On('qualification-added')]
    public function listenToAddedQualification($qualification, $priority)
    {
        $this->jobQualifications[] = [
            'description' => $qualification,
            'priorityLevel' => $priority,
        ];
    }

    #[On('qualification-updated')]
    public function listenToUpdatedQualification($index, $qualification, $priority)
    {
        $this->jobQualifications[$index]['description'] = $qualification;
        $this->jobQualifications[$index]['priorityLevel'] = $priority;
    }

    #[Computed]
    public function departments()
    {
        return Department::all()->pluck('department_name', 'department_id')->toArray();   
    }

    public function render()
    {
        return view('livewire.admin.job-title.create-job-title-form');
    }
}
