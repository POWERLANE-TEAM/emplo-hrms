<?php

namespace App\Livewire\Employee\Applicants;

use App\Models\Application;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
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
        return view('livewire.employee.applicants.show', ['application' => $this->application]);
    }
}
