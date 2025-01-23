<?php

namespace App\Livewire\Employee\Separation;

use Livewire\Attributes\Locked;
use App\Enums\FilePath;
use App\Models\CoeRequest;
use App\Models\Employee;
use App\Models\EmployeeDoc;
use App\Models\Resignation as ModelsResignation;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Resignation extends Component
{


    #[Locked]
    public bool $hasResignation;

    public Employee $employee;

    public ?ModelsResignation $resignation;

    public ?CoeRequest $coeReq;

    public function mount()
    {
        $this->employee = auth()->user()->account;

        $this->resignation = optional(optional(auth()->user()->account->resignations())->latest())->first();

        $this->hasResignation = $this->employee->resignations()->exists();

        if ($this->hasResignation) {
            $this->resignation->loadMissing('resigneeLifecycle','resignationLetter');
        }

        $this->coeReq = CoeRequest::where('requested_by', $this->employee->employee_id)->latest()->first();

    }

    public function download(){

        $file = $this->coeReq->empCoeDoc->file_path;

        if (Storage::disk('public')->exists($file)) {
            return Storage::disk('public')->download($file);
        }

    }


    public function render()
    {
        return view('livewire.employee.separation.resignation');
    }
}
