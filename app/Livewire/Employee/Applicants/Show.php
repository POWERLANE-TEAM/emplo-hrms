<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\ApplicationStatus;
use App\Http\Helpers\Timezone;
use App\Models\Application;
use App\Models\ApplicationExam;
use App\Models\InterviewParameter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    protected $listeners = ['refreshChanges' => '$refresh'];

    public Application $application;

    public ApplicationExam $applicationExam;

    public Collection $interviewParameters;

    protected $applicationId;

    protected bool $isPending;

    protected bool $isInitAssessment;

    protected bool $isFinalAssessment;

    protected $examSchedF;

    protected $initialInterviewSchedF;

    protected $resume;

    #[Locked]
    public bool $notYetExam = false;

    #[Locked]
    public bool $notYetInitInterview = false;

    #[Locked]
    public  string $evaluationNotice;

    #[Locked]
    public bool $isReadyForInitEvaluation = false;

    #[Locked]
    public $modalId;

    public function mount()
    {

    }


    public function boot()
    {

        // try {
            $this->applicationId = 'APL-' . $this->application->application_id;

            $this->resume = optional($this->application->documents->where('preemp_req_id', 17)->first())->file_path;

            // dd($this->resume);
            if($this->resume && !Storage::exists($this->resume)){
                // throw new \Exception('Resume not found');
            }

            $this->isPending = $this->application->application_status_id == ApplicationStatus::PENDING->value;


            $this->isInitAssessment = $this->application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED->value;
            $this->isFinalAssessment = $this->application->application_status_id == ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value;

            if($this->isInitAssessment|| $this->isFinalAssessment){

                $this->application->loadMissing('initialInterview');

                $this->applicationExam = ApplicationExam::where('application_id', $this->application->application_id)->first();

                $this->examSchedF = $this->formatSchedule(optional($this->applicationExam)->start_time);
                $this->initialInterviewSchedF = $this->formatSchedule(optional($this->application->initialInterview)->init_interview_at);


                if ($this->applicationExam->start_time) {
                    $examTime = Carbon::parse($this->applicationExam->start_time);

                    $this->notYetExam = !Carbon::now()->greaterThanOrEqualTo($examTime) && Carbon::now()->lessThan($examTime->addMinutes(5));

                } else {
                    // ung exam time nakalipas na
                    $this->notYetExam = false;
                }

                if ($this->application->initialInterview->init_interview_at) {
                    $interviewTime = Carbon::parse($this->application->initialInterview->init_interview_at);
                    $this->notYetInitInterview = !Carbon::now()->greaterThanOrEqualTo($interviewTime) && Carbon::now()->lessThan($interviewTime->addMinutes(5));
                } else {
                    // ung interview time nakalipas na
                    $this->notYetInitInterview = false;
                }

                // if interview hass occured
                if(!$this->notYetInitInterview){
                    $this->interviewParameters = InterviewParameter::all();
                }

                if($this->isInitAssessment && $this->notYetExam || $this->notYetInitInterview){
                    $this->evaluationNotice = 'The assign button(s) are currently disabled. They will be available once the scheduled date arrives.';
                }

                if(
                    !$this->notYetExam && !$this->notYetInitInterview &&
                    $this->application->initialInterview->init_interviewer &&
                    optional($this->applicationExam)->passed &&
                    optional($this->application->initialInterview)
                ){
                    $this->isReadyForInitEvaluation = true;
                }

            }

        // } catch (\Throwable $th) {
        //     report($th);
        // }
    }

    public function setFinalInterview()
    {
       dump();
    }

    public function render()
    {
        return view(
            'livewire.employee.applicants.show',
            [
                'applicationId' => $this->applicationId,
                'resume' => $this->resume,
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
