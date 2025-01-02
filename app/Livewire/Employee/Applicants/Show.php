<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\ApplicationStatus;
use App\Http\Helpers\Timezone;
use App\Models\Application;
use App\Models\ApplicationExam;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    public Application $application;

    public ApplicationExam $applicationExam;

    protected $applicationId;

    protected bool $isPending;

    protected bool $isInitAssessment;

    protected $examSchedF;

    protected $initialInterviewSchedF;

    #[Locked]
    public bool $notYetExam;

    #[Locked]
    public bool $notYetInterview;

    #[Locked]
    public  string $evaluationNotice;

    #[Locked]
    public bool $isReadyForInitEvaluation;

    #[Locked]
    public $modalId;


    public function boot()
    {

        try {
            $this->applicationId = 'APL-' . $this->application->application_id;
            $this->isPending = $this->application->application_status_id == ApplicationStatus::PENDING->value;

            $this->isInitAssessment = $this->application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED->value;

            if($this->isInitAssessment){

                $this->application->load('initialInterview');

                $this->applicationExam = ApplicationExam::where('application_id', $this->application->application_id)->first();

                $this->examSchedF = $this->formatSchedule(optional($this->applicationExam)->start_time);
                $this->initialInterviewSchedF = $this->formatSchedule(optional($this->application->initialInterview)->init_interview_at);


                if ($this->applicationExam->start_time) {
                    $this->notYetExam = !Carbon::now()->greaterThanOrEqualTo(Carbon::parse($this->applicationExam->start_time)->addMinutes(5));
                } else {
                    $this->notYetExam = false;
                }

                if ($this->application->initialInterview->init_interview_at) {
                    $this->notYetInterview = !Carbon::now()->greaterThanOrEqualTo(Carbon::parse($this->application->initialInterview->init_interview_at)->addMinutes(5));
                } else {
                    $this->notYetInterview = false;
                }

                if(!$this->isInitAssessment && $this->notYetExam || $this->notYetInterview){
                    $this->evaluationNotice = 'The assign button(s) are currently disabled. They will be available once the scheduled date arrives.';
                }

                if(
                    $this->notYetExam && $this->notYetInterview &&
                    $this->application->initialInterview->init_interviewer &&
                    $examPassed = true
                ){
                    $this->isReadyForInitEvaluation = true;
                }
            }

        } catch (\Throwable $th) {
            report($th);
        }
    }

    public function render()
    {

        return view(
            'livewire.employee.applicants.show',
            [
                'applicationId' => $this->applicationId,
                'isPending' => $this->isPending,
                'isInitAssessment' => $this->isInitAssessment,
                'examSchedF' => $this->examSchedF,
                'initialInterviewSchedF' => $this->initialInterviewSchedF
            ]
        );
    }

    protected function formatSchedule($date)
    {
        return $date ? Carbon::parse($date)->setTimezone(Timezone::get())->format('m/d/y - h:iA') : 'Not Set';
    }
}
