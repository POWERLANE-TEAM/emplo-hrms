<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\UserPermission;
use App\Http\Controllers\ApplicationExamController;
use App\Livewire\Forms\ScheduleForm;
use App\Models\Application;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class SetExaminationDate extends Component
{

    public ScheduleForm $examination;

    public Application $application;

    public function mount(Application $application)
    {
        $this->application = $application;
    }

    #[On('submit-exam-sched-form')]
    public function store()
    {
        if (! auth()->user()->hasPermissionTo(UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE)) {
            abort(403);
        }

        $validated = $this->validate();

        $validated['applicationId'] = $this->application->application_id;

        $controller = new ApplicationExamController();

        $controller->store($validated, true);
    }

    public function render()
    {
        return view('livewire.employee.applicants.set-examination-date', ['applicationId' => $this->application->application_id]);
    }
}
