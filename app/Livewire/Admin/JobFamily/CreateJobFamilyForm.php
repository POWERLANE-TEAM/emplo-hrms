<?php

namespace App\Livewire\Admin\JobFamily;

use Livewire\Component;
use App\Models\JobFamily;
use App\Enums\UserPermission;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateJobFamilyForm extends Component
{
    #[Validate('required')]
    public $jobFamilyName;

    #[Validate('nullable')]
    public $jobFamilyDesc;

    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_JOB_FAMILY)) {
            $this->reset();

            abort(403);
        }

        $this->validate();

        DB::transaction(function () {
            JobFamily::create([
                'job_family_name' => $this->jobFamilyName,
                'job_family_desc' => $this->jobFamilyDesc,
                'office_head' => null,
            ]);            
        });

        $this->reset();

        $this->dispatch('job-family-created');
    }

    public function render()
    {
        return view('livewire.admin.job-family.create-job-family-form');
    }
}
