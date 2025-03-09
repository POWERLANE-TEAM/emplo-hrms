<?php

namespace App\Livewire\Employee\Modal;

use App\Models\PreempRequirement;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class PreEmploymentPreview extends Component
{
    public PreempRequirement $pre_employment_req;

    public $file;

    public function mount()
    {
        try {
            $application = auth()->user()->account->application;

            $filePath = optional($application->documents()
                ->where('preemp_req_id', $this->pre_employment_req->preemp_req_id)
                ->latest()
                ->first())
                ->file_path;

            if ($filePath) {
                $this->file = Storage::disk('public')->url($filePath);
            }
        } catch (\Throwable $th) {
            report($th);
        }

    }

    public function render()
    {
        return view('livewire.employee.modal.pre-employment-preview');
    }
}
