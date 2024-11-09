<?php

namespace App\Livewire\Employee\Applicants;

use App\Models\Application;
use Livewire\Component;

class Show extends Component
{

    private Application $application;

    public function mount(Application $application)
    {
        $this->application = $application;
    }

    public function render()
    {
        return view('livewire.employee.applicants.show', ['application' => $this->application]);
    }
}
