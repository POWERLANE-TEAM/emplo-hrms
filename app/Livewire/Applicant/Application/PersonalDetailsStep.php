<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\AccountType;
use App\Enums\Sex;
use App\Enums\UserPermission;
use App\Events\Guest\ResumeParsed;
use App\Jobs\Guest\ParseResumeJob;
use App\Livewire\Forms\DateForm;
use App\Livewire\Forms\FileForm;
use App\Livewire\Forms\PersonNameForm;
use App\Traits\Applicant;
use App\Traits\HasObjectForm;
use App\Traits\NeedsAuthBroadcastId;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Spatie\LivewireFilepond\WithFilePond;
use Spatie\LivewireWizard\Components\StepComponent;

class PersonalDetailsStep extends StepComponent
{

    use WithFilePond, HasObjectForm, NeedsAuthBroadcastId, Applicant;

    /**
     * |--------------------------------------------------------------------------
     * | Wire model properties
     * |--------------------------------------------------------------------------
     */
    public $displayProfile;

    public $applicantName;

    public $applicantBirth;

    public ?string $sexAtBirth;

    public $form = [
        'applicantName' => ['firstName' => '', 'middleName' => '', 'lastName' => ''],
        'applicantBirth' => '',
    ];

    /**
     * |--------------------------------------------------------------------------
     * | END Wire model properties
     * |--------------------------------------------------------------------------
     */

    // persistent file url
    // it is fetched from resume upload step state
    public $resumeUrl;

    // persistent resume file path on the server temp directory
    // it is fetched from resume upload step state
    public $resumePath;

    // persistent file url
    public $displayProfileUrl;

    // persistent file path on the server temp directory
    // used to retrieve file object when rehydrating and also saving the file object permanently to user records
    public $displayProfilePath;

    // parsed resume from document ai
    public ?array $parsedResume = null;

    // this hold the name segments from the parsed resume
    // this is used to suggest in the name fields
    public $parsedNameSegment;

    // used to tag the step is complete
    // has no actual use yet
    public bool $isValid = false;

    public function mount()
    {

        if (Auth::check()) {
            if (self::applicantOrYet(!Auth::user()->hasPermissionTo(UserPermission::VIEW_JOB_APPLICATION_FORM->value), true));
            else self::hasApplication(true);
        } else abort(401);

        // get the resume file property state from the resume upload step
        $resumeState = $this->state()->forStep('form.applicant.resume-upload-step');

        // reset parsed resume if the resume uploaded is changed
        if (isset($resumeState['resumeUrl']) && $this->resumeUrl != $resumeState['resumeUrl']) {

            if (isset($this->resumePath) && file_exists($this->resumePath)) {
                $oldContent = file_get_contents($this->resumePath);
            }

            if (isset($resumeState['resumePath']) && file_exists($resumeState['resumePath'])) {
                $currentContent = file_get_contents($resumeState['resumePath']);
            }

            // compare file content
            // this is to save document ai request
            if (isset($oldContent) && isset($currentContent)) {
                if ($oldContent != $currentContent) {
                    $this->parsedResume = null;
                }
            }

            $this->resumeUrl = $resumeState['resumeUrl'];
            $this->resumePath = $resumeState['resumePath'];
        }

        // add check if user approves consent to parse resume
        if (true && empty($this->parsedResume) && isset($this->resumePath)) {
            ParseResumeJob::dispatch($this->resumePath, self::getBroadcastId());
        }


        // For testing purposes not relying on Document AI API

        // ResumeParsed::dispatch([
        //     'employee_education' => 'Bachelor of Arts in Communication, Ateneo de Manila University',
        //     'employee_contact' => '+63-961-5719',
        //     'employee_email' => 'fernando.poe.jrs@gmail.com',
        //     'employee_experience' => 'Globe Telecom - 3 years as Project Manager\nRobinsons Land - 5 years as Marketing Specialist',
        //     'employee_name' => 'Grace, Fernando Poe Jr.',
        //     'employee_skills' => 'Project Management, Customer Service, Problem Solving',
        // ], self::getBroadcastId());

        // if (empty($this->parsedResume)) {
        //     $this->parsedResume = [
        //         'employee_education' => 'Bachelor of Arts in Communication, Ateneo de Manila University',
        //         'employee_contact' => '+63-961-5719',
        //         'employee_email' => 'fernando.poe.jrs@gmail.com',
        //         'employee_experience' => 'Globe Telecom - 3 years as Project Manager\nRobinsons Land - 5 years as Marketing Specialist',
        //         'employee_name' => 'Grace, Fernando Poe Jr.',
        //         'employee_skills' => 'Project Management, Customer Service, Problem Solving',
        //     ];
        // }

        // $this->updateParsedNameSegment();
    }

    public function boot()
    {

        // Copied fix here  https://github.com/spatie/laravel-livewire-wizard/discussions/100#discussioncomment-10125903
        // still broken
        $this->mountWithObjectForm(PersonNameForm::class, 'applicantName');
        $this->mountWithObjectForm(DateForm::class, 'applicantBirth');

        if (isset($this->displayProfile) && !is_array($this->displayProfile) && $this->displayProfile instanceof \Illuminate\Http\UploadedFile) {

            // save persistent file properties that is needed when rehydrating
            try {
                $this->displayProfileUrl = $this->displayProfile->temporaryUrl();
                $this->displayProfilePath = $this->displayProfile->getRealPath();
            } catch (\Exception $e) {
                report($e->getMessage());
            }
        } else if (isset($this->displayProfilePath) && empty($this->displayProfile)) {

            // rehydrate the file object
            // however the file object is not readable by the filepond
            if (file_exists($this->displayProfilePath)) {
                $tempFile = new \Illuminate\Http\UploadedFile(
                    $this->displayProfilePath,
                    basename($this->displayProfilePath),
                    null,
                    null,
                    true
                );

                $livewireTempFile = TemporaryUploadedFile::createFromLivewire($tempFile);
                $this->displayProfile = $livewireTempFile;
            }
        }

        // Rehydrate the PersonName form object
        $this->applicantName->firstName = $this->form['applicantName']['firstName'];
        $this->applicantName->middleName = $this->form['applicantName']['middleName'];
        $this->applicantName->lastName = $this->form['applicantName']['lastName'];

        // Rehydrate the Date form object
        $this->applicantBirth->date = $this->form['applicantBirth'];

        // Rehydrate the Date form object validation rules
        // atleast 18 years old
        $this->applicantBirth->setMaxDate(Carbon::now()->subYears(18)->toDateString());

        // at most 65 years old
        $this->applicantBirth->setMinDate(Carbon::now()->subYears(65)->endOfYear()->toDateString());


        if (!empty($this->displayProfileUrl)) {
            // this supposed to be the fix for the filepond issue
            // but will cause validation to throw must be of file type
            // $this->displayProfile = $this->displayProfileUrl;
        }
    }

    #[Computed]
    protected function rules()
    {
        // use file form object to generate the file validation rules
        $displayProfile = new FileForm($this, 'displayProfile');

        $maxSize = 'xs';
        $required = true;

        $displayProfile->setToImageMode();
        $displayProfile->setMaxSize($maxSize);
        $maxFileSize = $displayProfile->getMaxFileSize();
        $fileTypes = $displayProfile->getAcceptedFileTypes();
        $displayProfile->setRequired($required);

        $displayProfileRules = $displayProfile->rules();

        return [
            'sexAtBirth' => 'required|in:' . implode(',', array_keys(Sex::options())),
            'displayProfile' => 'bail|required|file|max:' . $maxFileSize . '|mimes:' . $fileTypes,
        ];
    }

    public function validateNow()
    {

        // Rehydrate the PersonName form object
        $this->applicantName->firstName = $this->form['applicantName']['firstName'];
        $this->applicantName->middleName = $this->form['applicantName']['middleName'];
        $this->applicantName->lastName = $this->form['applicantName']['lastName'];

        // Rehydrate the Date form object
        $this->applicantBirth->date = $this->form['applicantBirth'];

        $this->isValid = false;

        $this->validate();

        $this->isValid = true;

        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'title' => 'Personal Details',
            // tags if complete and fields were valid
            'isComplete' => $this->isValid,
        ];
    }

    // post process the form data
    public function updating()
    {
        // Capitalize each word in applicant name
        $this->form['applicantName']['firstName'] = ucwords(strtolower($this->form['applicantName']['firstName']));
        $this->form['applicantName']['middleName'] = ucfirst(strtolower($this->form['applicantName']['middleName']));
        $this->form['applicantName']['lastName'] = ucwords(strtolower($this->form['applicantName']['lastName']));
    }

    // websocket stuffs
    public function getListeners()
    {
        $authBroadcastId = self::getBroadcastId();

        return [
            "echo-private:applicant.applying.{$authBroadcastId},Guest.ResumeParsed" => 'updateParsedResume',
        ];
    }

    #[Computed]
    public static function getBroadcastId()
    {
        return self::generateAuthId();
    }

    public function updateParsedResume($event)
    {
        try {
            $this->parsedResume = $event['parsedResume'];
            $this->updateParsedNameSegment();
        } catch (\Throwable $th) {
            report($th);
        }
    }

    // segment the parsed resume applicant name
    // this is not the solution to know which is first name etc
    // but this will be used as name field suggestions
    public function updateParsedNameSegment()
    {
        if (!empty($this->parsedResume['employee_name'])) {
            $parsedFullName = preg_replace('/[^\p{L}\s.-]/u', '', $this->parsedResume['employee_name']);
            $parsedFullName = ucwords(strtolower($parsedFullName));
            $this->parsedNameSegment = explode(' ', $parsedFullName);
        }
    }

    /**
     * Accessor for sexes, returning key / value pairs of enum cases and labels.
     *
     * @return array
     */
    #[Computed]
    public function sexes()
    {
        return Sex::options();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('livewire.applicant.application.personal-details-step');
    }
}
