<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\UserPermission;
use App\Http\Controllers\InitialInterviewController;
use App\Http\Helpers\Timezone;
use App\Livewire\Forms\ScheduleForm;
use App\Models\Application;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class SetInitInterviewDate extends Component
{
    public ScheduleForm $interview;

    public Application $application;

    #[Locked]
    public ?string $postMethod = null;

    #[Locked]
    public bool $overrideInputContainerClass = false;

    #[Locked]
    public bool $overrideDateWrapper = false;

    #[Locked]
    public bool $overrideTimeWrapper = false;

    #[Locked]
    public array $inputGroupAttributes = [];

    #[Locked]
    public array $dateWrapAttributes = [];

    #[Locked]
    public array $timeWrapAttributes = [];

    public function mount()
    {

        $startTime = optional($this->application->initialInterview)->init_interview_at;
        $this->interview->date = $startTime ? Carbon::parse($startTime)->setTimezone(Timezone::get())->toDateString() : null;
        $this->interview->time = $startTime ? Carbon::parse($startTime)->setTimezone(Timezone::get())->toTimeString() : null;

        if (! $this->overrideInputContainerClass) {
            $this->inputGroupAttributes = array_merge($this->inputGroupAttributes, ['class' => 'input-group flex-md-nowrap gap-1 min-w-100']);
        }

        if (! $this->overrideDateWrapper) {
            $this->dateWrapAttributes = array_merge($this->dateWrapAttributes, ['class' => 'col-12 col-md-6']);
        }

        if (! $this->timeWrapAttributes) {
            $this->timeWrapAttributes = array_merge($this->timeWrapAttributes, ['class' => 'col-12 col-md-6']);
        }

        if ($startTime) {
            $this->postMethod = 'PATCH';
        }

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

        if ($this->postMethod == 'PATCH') {
            $controller->update($validated, true);
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Initial interview has been rescheduled.',
            ]);
        } else {
            $controller->store($validated, true);
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Initial interview has been set.',
            ]);
        }

        $this->dispatch('refreshChanges');
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
