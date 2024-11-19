<?php

namespace App\Livewire\Employee\Modal\Applicant\Resume;

use App\Models\Application;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Approve extends Component
{

    private Application $application;

    #[Locked]
    public $modalId;

    public function mount(Application $application)
    {
        $this->application = $application;
    }

    public function render()
    {
        return view('livewire.employee.modal.applicant.resume.approve', ['application' => $this->application]);
    }
}
