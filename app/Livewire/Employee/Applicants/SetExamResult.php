<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\BasicEvalStatus;
use App\Http\Controllers\ApplicationExamController;
use App\Models\Application;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class SetExamResult extends Component
{

    public Application $application;

    public $examResult;

    public function mount()
    {
        // bool doesnt work i need to cast it to int
        $this->examResult = (int) $this->application->exam->passed;
    }

    #[Computed]
    public function basicEvalStatus()
    {
        return BasicEvalStatus::options();
    }

    public function save(ApplicationExamController $controller)
    {
        $validated = $this->validate([
            'examResult' => 'required'
        ]);

        $validated['applicationId'] = $this->application->application_id;

        $controller->update($validated, true);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Exam result has been updated',
        ]);

        $this->dispatch('refreshChanges');
    }

    public function render()
    {
        return view('livewire.employee.applicants.set-exam-result');
    }
}
