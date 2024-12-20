<?php

namespace App\Livewire\Applicant\Stages;

use App\Http\Helpers\Timezone;
use App\Models\Application;
use App\Models\ApplicationExam;
use Carbon\Carbon;
use Livewire\Component;

class Scheduled extends Component
{

    public Application $application;

    public ApplicationExam $applicationExam;

    public $examStartTimeF;

    public $initialInterviewTimeF;

    public function mount(Application $application)
    {
        $this->application = $application;
        $this->applicationExam = ApplicationExam::where('application_id', $application->application_id)->first();
        $this->examStartTimeF = Carbon::parse($this->applicationExam->start_time)->setTimezone(Timezone::get())->format('m/d/y - h:iA');


        $this->initialInterviewTimeF = Carbon::parse($this->application->initialInterview->init_interview_at)->setTimezone(Timezone::get())->format('m/d/y - h:iA');
    }

    public function render()
    {
        return view('livewire.applicant.stages.scheduled');
    }
}
