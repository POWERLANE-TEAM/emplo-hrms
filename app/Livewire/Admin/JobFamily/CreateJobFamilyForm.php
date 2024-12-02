<?php

namespace App\Livewire\Admin\JobFamily;

use Livewire\Component;
use App\Models\Employee;
use App\Models\JobFamily;
use App\Enums\UserPermission;
use Livewire\Attributes\Validate;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateJobFamilyForm extends Component
{
    #[Validate]
    public $state = [
        'name' => '',
        'description' => '',
        'head' => null,
    ];

    public $query;

    public Collection $employees;

    public function mount()
    {
        $this->reset();
    }

    public function resetQuery()
    {
        $this->resetExcept('state');
    }

    public function rules()
    {
        return [
            'state.name' => 'required',
            'state.description' => 'nullable',
            'state.head' => 'required|integer|exists:job_families,job_family_id',
        ];
    }

    public function messages()
    {
        return [
            'state.name' => __('Job family name is required.'),
            'state.description' => __('Whatever'),
            'state.head' => __('Office head is required.'),
        ];
    }

    public function updatedQuery()
    {
        $this->employees = Employee::whereLike('first_name', "%{$this->query}%")
                                ->orWhereLike('middle_name', "%{$this->query}%")
                                ->orWhereLike('last_name', "%{$this->query}%")
                                ->get()
                                ->map(function ($item) {
                                    return (object) [
                                        'id' => $item->employee_id,
                                        'fullName' => $item->full_name 
                                    ];
                                });
    }

    public function selectEmployee(int $id)
    {
        $employee = Employee::find($id);

        if (! $employee) {
            // do smth, error msgs
        } else {
            $this->state['head'] = $employee->employee_id;
            $this->query = $employee->full_name;
        }
        $this->resetErrorBag();
    }

    public function save()
    {
        if (! Auth::user()->hasPermissionTo(UserPermission::CREATE_JOB_FAMILY)) {
            $this->reset();

            abort(403);
        }

        $this->validate();

        DB::transaction(function () {
            JobFamily::create([
                'job_family_name' => $this->state['name'],
                'job_family_desc' => $this->state['description'],
                'office_head' => $this->state['head'],
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
