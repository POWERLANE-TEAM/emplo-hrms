<?php

namespace App\Livewire\Employee\Separation;

use App\Http\Controllers\Separation\ResignationController;
use App\Models\CoeRequest;
use App\Models\Employee;
use App\Models\Resignation as ModelsResignation;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
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
            $this->resignation->loadMissing('resigneeLifecycle', 'resignationLetter');
        }

        $this->coeReq = CoeRequest::where('requested_by', $this->employee->employee_id)->latest()->first();

    }

    public function retract(ResignationController $controller)
    {

        try {
            $response = $controller->update(['resignation_id' => $this->resignation->resignation_id, 'retracted_at' => now()], validated: true);

            if ($response instanceof \Illuminate\Http\JsonResponse && $response->getStatusCode() == 400) {
                $this->dispatch('show-toast', [
                    'type' => 'danger',
                    'message' => $response->getData()->message,
                ]);
            } else {
                $this->dispatch('show-toast', [
                    'type' => 'success',
                    'message' => 'Resignation retracted successfully.',
                ]);
            }
        } catch (\Exception $e) {
            report($e);
            $this->dispatch('show-toast', [
                'type' => 'danger',
                'message' => 'An error occurred while retracting the resignation.',
            ]);
        }

    }

    public function download()
    {

        $file = $this->coeReq->empCoeDoc->file_path;

        if (Storage::disk('public')->exists($file)) {
            $downloadName = 'Certificate_of_Employment.pdf'; // Specify the desired download name here

            return Storage::disk('public')->download($file, $downloadName);
        }

    }

    public function render()
    {
        return view('livewire.employee.separation.resignation');
    }
}
