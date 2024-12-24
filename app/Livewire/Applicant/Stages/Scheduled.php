<?php

namespace App\Livewire\Applicant\Stages;

use App\Http\Helpers\Timezone;
use App\Models\Application;
use App\Models\ApplicationExam;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Scheduled extends Component
{

    public Application $application;

    public ApplicationExam $applicationExam;

    #[Locked]
    public $examStartTimeF;

    #[Locked]
    public $initialInterviewTimeF;

    public function mount(Application $application)
    {

        $this->applicationExam = ApplicationExam::where('application_id', $application->application_id)->first();

        $examStartTime = optional($this->applicationExam)->start_time;
        $this->examStartTimeF = $examStartTime ? Carbon::parse($examStartTime)->setTimezone(Timezone::get())->format('m/d/y - h:iA') : 'Not Set';

        $initialInterviewTime = optional($this->application->initialInterview)->init_interview_at;
        $this->initialInterviewTimeF = $initialInterviewTime ? Carbon::parse($initialInterviewTime)->setTimezone(Timezone::get())->format('m/d/y - h:iA') : 'Not Set';
    }

    public function render()
    {
        return view('livewire.applicant.stages.scheduled');
    }
}
