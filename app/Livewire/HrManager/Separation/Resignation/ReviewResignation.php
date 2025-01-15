<?php

namespace App\Livewire\HrManager\Separation\Resignation;

use App\Enums\FilePath;
use App\Models\Employee;
use Livewire\Component;

class ReviewResignation extends Component
{

    public Employee $employee;

    public bool $hasResignation;

    public function mount()
    {
        $this->hasResignation = $this->employee->documents()->where('file_path', 'like', '%' . FilePath::RESIGNATION->value . '%')->exists();

        if ($this->hasResignation) {
            $this->employee->loadMissing('lifecycle','documents');
        }
    }

    public function saveApproval()
    {
        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Resignation approved successfully.',
        ]);

        $this->dispatch('changes-saved', ['modalId' => 'confirmApproval']);
    }

    public function saveRejection()
    {
        $this->dispatch('show-toast', [
            'type' => 'danger',
            'message' => 'Resignation rejected.',
        ]);

        $this->dispatch('changes-saved', ['modalId' => 'confirmRejection']);
    }

    public function render()
    {
        return view('livewire.hr-manager.separation.resignation.review-resignation');
    }
}
