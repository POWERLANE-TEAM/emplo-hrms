<?php

namespace App\Livewire\Employee\Applicants;

use App\Enums\ApplicationStatus;
use App\Http\Helpers\Timezone;
use App\Models\Application;
use App\Models\ApplicationExam;
use App\Models\InterviewParameter;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
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

    protected  $timezone;

    protected $applicationId;

    protected bool $isPending;

    protected bool $isInitAssessment;

    protected bool $isFinalAssessment;

    protected $examSchedF;

    protected $initialInterviewSchedF;

    protected $finalInterviewSchedF;

    protected $resume;

    #[Locked]
    public bool $notYetExam = false;

    #[Locked]
    public bool $notYetInitInterview = false;

    #[Locked]
    public bool $notYetFinalInterview = false;

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

        try {
            $timezone = $this->timezone = new DateTimeZone(config('app.timezone'));

            $this->applicationId = 'APL-' . $this->application->application_id;

            $this->resume = optional($this->application->documents->where('preemp_req_id', 17)->first())->file_path;

            // dd($this->resume);
            if($this->resume && !Storage::exists($this->resume)){
                // throw new \Exception('Resume not found');
            }

            $this->isPending = $this->application->application_status_id == ApplicationStatus::PENDING->value;


            $this->isInitAssessment = $this->application->application_status_id == ApplicationStatus::ASSESSMENT_SCHEDULED->value;
            $this->isFinalAssessment =  $this->application->application_status_id == ApplicationStatus::FINAL_INTERVIEW_SCHEDULED->value;
            $this->isFinalAssessment =  $this->isFinalAssessment && $this->application->initialInterview->is_init_interview_passed;

            if($this->isInitAssessment|| $this->isFinalAssessment){

                $this->application->loadMissing('initialInterview');

                $this->applicationExam = ApplicationExam::where('application_id', $this->application->application_id)->first();


                $this->examSchedF = $this->formatSchedule(optional($this->applicationExam)->start_time);
                $this->initialInterviewSchedF = $this->formatSchedule(optional($this->application->initialInterview)->init_interview_at);
                $this->finalInterviewSchedF = $this->formatSchedule(optional($this->application->finalInterview)->final_interview_at);

                if ($this->applicationExam->start_time) {
                    $examTime = $this->applicationExam->start_time;
                    $examTime = $examTime ? new DateTime($examTime, $timezone) : null;

                    $this->notYetExam = $examTime && new DateTime('now', $timezone) <= (clone $examTime)->modify('+5 minutes');

                } else {
                    // ung exam time nakalipas na
                    $this->notYetExam = false;
                }

                if ($this->application->initialInterview->init_interview_at) {
                    $initInterviewTime = $this->application->initialInterview->init_interview_at;
                    $initInterviewTime = $initInterviewTime ? new DateTime($initInterviewTime, $timezone) : null;

                    $this->notYetInitInterview = $initInterviewTime && new DateTime('now', $timezone) <= (clone $initInterviewTime)->modify('+5 minutes');
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



                if (optional($this->application->finalInterview)->final_interview_at) {
                    $finalInterviewTime = new DateTime($this->application->finalInterview->final_interview_at, $timezone);
                    $currentTime = new DateTime('now', new DateTimeZone('now', $timezone));

                    $this->notYetFinalInterview = $currentTime &&  new DateTime() <= (clone $finalInterviewTime)->modify('+5 minutes');

                } else {
                    // ung interview time nakalipas na
                    $this->notYetFinalInterview = false;
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
                'resume' => $this->resume,
                'isPending' => $this->isPending,
                'isInitAssessment' => $this->isInitAssessment,
                'examSchedF' => $this->examSchedF,
                'initialInterviewSchedF' => $this->initialInterviewSchedF,
                'finalInterviewSchedF' => $this->finalInterviewSchedF,
                'isFinalAssessment' => $this->isFinalAssessment
            ]
        );
    }

    public function makeEmployeeInst()
    {

        $applicant = $this->application->applicant;

        $this->application->update([
            'application_status_id' => ApplicationStatus::APPROVED->value,
            'is_passed' => true,
        ]);

        return redirect()->route('employee.applications', ['applicationStatus' => 'qualified'])->with('show-toast', [
            'type' => 'success',
            'message' => 'Application Approved.',
        ]);

    }

    protected function formatSchedule($date)
    {
        return $date ? Carbon::parse($date)->setTimezone(Timezone::get())->format('m/d/y - h:iA') : 'Not Set';
    }
}
