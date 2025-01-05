<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\UserPermission;
use App\Http\Controllers\ApplicationExamController;
use App\Http\Helpers\Timezone;
use App\Livewire\Forms\ScheduleForm;
use App\Models\Application;
use App\Models\ApplicationExam;
use Carbon\Carbon;
use Illuminate\View\ComponentAttributeBag;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class SetExaminationDate extends Component
{
    public ScheduleForm $examination;

    public Application $application;

    #[Locked]
    public string $postMethod;

    public bool $overrideContainerClass = false;
    
    public bool $overrideContainerClass = false;

    public array $dateWrapAttributes;

    public array $timeWrapAttributes;

    public function mount()
    {

        try {

            $exam = ApplicationExam::where('application_id', $this->application->application_id)->first();
            $startTime = optional( $exam)->start_time;
            $this->examination->date = $startTime ? Carbon::parse($startTime)->setTimezone(Timezone::get())->toDateString() : null;
            $this->examination->time = $startTime ? Carbon::parse($startTime)->setTimezone(Timezone::get())->toTimeString() : null;

            if($exam){
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

        $controller->store($validated, true);
    }

    public function render()
    {

        if (!$this->overrideContainerClass) {
            $this->dateWrapAttributes = array_merge($this->dateWrapAttributes, ['class' => 'col-12 col-md-6']);
        }


        return view('livewire.employee.applicants.set-examination-date', ['applicationId' => $this->application->application_id]);
    }
}
