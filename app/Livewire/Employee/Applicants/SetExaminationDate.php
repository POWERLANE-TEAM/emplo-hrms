<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\UserPermission;
use App\Http\Controllers\ApplicationExamController;
use App\Http\Helpers\Timezone;
use App\Livewire\Forms\ScheduleForm;
use App\Models\Application;
use App\Models\ApplicationExam;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class SetExaminationDate extends Component
{
    public ScheduleForm $examination;

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

        try {

            $exam = ApplicationExam::where('application_id', $this->application->application_id)->first();
            $startTime = optional($exam)->start_time;
            $this->examination->date = $startTime ? Carbon::parse($startTime)->setTimezone(Timezone::get())->toDateString() : null;
            $this->examination->time = $startTime ? Carbon::parse($startTime)->setTimezone(Timezone::get())->toTimeString() : null;

            if (! $this->overrideInputContainerClass) {
                $this->inputGroupAttributes = array_merge($this->inputGroupAttributes, ['class' => 'input-group flex-md-nowrap gap-1 min-w-100']);
            }

            if (! $this->overrideDateWrapper) {
                $this->dateWrapAttributes = array_merge($this->dateWrapAttributes, ['class' => 'col-12 col-md-6']);
            }

            if (! $this->timeWrapAttributes) {
                $this->timeWrapAttributes = array_merge($this->timeWrapAttributes, ['class' => 'col-12 col-md-6']);
            }

            if ($exam) {
                $this->postMethod = 'PATCH';
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }

    #[On('submit-exam-sched-form')]
    public function store()
    {
        if (! auth()->user()->hasPermissionTo(UserPermission::CREATE_APPLICANT_EXAM_SCHEDULE)) {
            abort(403);
        }

        $validated = $this->validate();

        $validated['applicationId'] = $this->application->application_id;

        $controller = new ApplicationExamController;

        if ($this->postMethod == 'PATCH') {
            $controller->update($validated, true);
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Exam schedule has been rescheduled.',
            ]);
        } else {
            $controller->store($validated, true);
            $this->dispatch('show-toast', [
                'type' => 'success',
                'message' => 'Exam schedule has been set.',
            ]);
        }

        $this->dispatch('refreshChanges');
    }

    public function render()
    {

        return view('livewire.employee.applicants.set-examination-date', [
            'applicationId' => $this->application->application_id,
            'overrideInputContainerClass' => $this->overrideInputContainerClass,
            'overrideDateWrapper' => $this->overrideDateWrapper,
            'overrideTimeWrapper' => $this->overrideTimeWrapper,
            'inputGroupAttributes' => $this->inputGroupAttributes,
            'dateWrapAttributes' => $this->dateWrapAttributes,
            'timeWrapAttributes' => $this->timeWrapAttributes,
        ]);
    }
}
