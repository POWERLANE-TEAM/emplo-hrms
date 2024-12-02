<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\UserPermission;
use App\Http\Controllers\InitialInterviewController;
use App\Livewire\Forms\ScheduleForm;
use App\Models\Application;
use Livewire\Attributes\On;
use Livewire\Component;

class SetInitInterviewDate extends Component
{
    public ScheduleForm $interview;

    public Application $application;

    public function mount(Application $application)
    {
        $this->application = $application;
    }

    #[On('submit-init-interview-sched-form')]
    public function store()
    {

        if (! auth()->user()->hasPermissionTo(UserPermission::CREATE_APPLICANT_INIT_INTERVIEW_SCHEDULE)) {
            abort(403);
        }

        $validated = $this->validate();

        $validated['applicationId'] = $this->application->application_id;

        $controller = new InitialInterviewController;

        $controller->store($validated, true);
    }

    /**
     * Handle the Examination date must not be the same as the Interview date.
     *
     * @return void
     */
    #[On('examination-date-event')]
    public function setInterviewMinDate($minDate)
    {

        $tomorrow = date('Y-m-d', strtotime('+1 day', strtotime($minDate)));
        if (strtotime($minDate) >= strtotime(date('Y-m-d'))) {
            $this->interview->setMinDate($minDate);
        }
    }

    public function render()
    {
        return view('livewire.employee.applicants.set-init-interview-date', ['applicationId' => $this->application->application_id]);
    }
}
