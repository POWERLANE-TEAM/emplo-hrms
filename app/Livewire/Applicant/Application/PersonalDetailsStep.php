<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\Sex;
use App\Enums\UserPermission;
use App\Events\Guest\ResumeParsed;
use App\Http\Requests\PersonNameRequest;
use App\Jobs\Guest\ParseResumeJob;
use App\Rules\EmailRule;
use App\Rules\MobileNumberRule;
use App\Rules\ProfilePhotoValidationRule;
use App\Rules\WorkAgeRule;
use App\Traits\Applicant;
use App\Traits\HasObjectForm;
use App\Traits\NeedsAuthBroadcastId;
use Closure;
use DateTime;
use DateTimeZone;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\LivewireFilepond\WithFilePond;
use Spatie\LivewireWizard\Components\StepComponent;

class PersonalDetailsStep extends StepComponent
{

    use WithFilePond, NeedsAuthBroadcastId;

    /**
     * |--------------------------------------------------------------------------
     * | Wire model properties
     * |--------------------------------------------------------------------------
     */

    public $displayProfile;

    public $applicant = [
        'name' => ['firstName' => '', 'middleName' => '', 'lastName' => ''],
        'birth' => '',
        'email' => '',
        'mobileNumber' => '',
    ];

    public ?string $sexAtBirth;
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
        if (auth()->check())  $this->applicant['email'] = auth()->user()->email;

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
            if (config('services.google.document_ai.enabled')) {
                $this->dispatch('show-toast', [
                    'type' => 'info',
                    'message' => 'Your resume is being parsed. Data will be available shortly.',
                ]);
            }
            ParseResumeJob::dispatch($this->resumePath, self::getBroadcastId());
        }


        // // For testing purposes not relying on Document AI API

        // ResumeParsed::dispatch([
        //     'employee_education' => 'Bachelor of Arts in Communication, Ateneo de Manila University',
        //     'employee_contact' => '+63-961-571-0923',
        //     'employee_email' => 'fernando.poe.jrs@gmail.com',
        //     'employee_experience' => 'Globe Telecom - 3 years as Project Manager\nRobinsons Land - 5 years as Marketing Specialist',
        //     'employee_name' => 'Grace, Fernando Poe Jr.',
        //     'employee_skills' => 'Project Management, Customer Service, Problem Solving',
        // ], self::getBroadcastId());

        // if (empty($this->parsedResume)) {
        //     $this->parsedResume = [
        //         'employee_education' => 'Bachelor of Arts in Communication, Ateneo de Manila University',
        //         'employee_contact' => '+63-961-571-0923',
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


        if (!empty($this->displayProfileUrl)) {
            // this supposed to be the fix for the filepond issue
            // but will cause validation to throw must be of file type
            // $this->displayProfile = $this->displayProfileUrl;
        }
    }

    protected function convertApplicantBirthToTimezone()
    {
        $timezone = config('app.server_timezone', 'Asia/Manila');
        $dateTimeZone = new DateTimeZone($timezone);

        try {
            $date = new DateTime($this->applicant['birth']);
            $date->setTimezone($dateTimeZone);
            $this->applicant['birth'] = $date->format('Y-m-d');
        } catch (\Exception $e) {
            report('Error converting applicant birth date to timezone: ' . $e->getMessage());
        }
    }


    protected function rules()
    {

        $displayProfileRule = new ProfilePhotoValidationRule(null, 'lg', true);

        $personNameRequest = new PersonNameRequest();

        $nameRules = [];
        foreach ($personNameRequest->rules() as $key => $rule) {
            $nameRules["applicant.name.$key"] = $rule;
        }

        return array_merge($nameRules, [
            'sexAtBirth' => 'required|in:' . implode(',', array_keys(Sex::options())),
            'displayProfile' => $displayProfileRule->getRule(),
            'applicant.mobileNumber' => ['required', MobileNumberRule::getRule()],
            'applicant.email' => 'required|' . (new EmailRule(false))->getRule(),
            'applicant.birth' => new WorkAgeRule(),
        ]);
    }

    protected function validationAttributes()
    {
        $personNameRequest = new PersonNameRequest();

        $nameAttributes = [];
        foreach ($personNameRequest->attributes() as $key => $attribute) {
            $nameAttributes["applicant.name.$key"] = $attribute;
        }

        return array_merge($nameAttributes, [
            'sexAtBirth' => 'sex at birth',
            'displayProfile' => 'profile photo',
            'applicant.mobileNumber' => 'mobile number',
            'applicant.email' => 'email address',
            'applicant.birth' => 'birth date',
        ]);
    }

    public function validateNow()
    {
        $this->convertApplicantBirthToTimezone();

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
        $this->applicant['name']['firstName'] = ucwords(strtolower($this->applicant['name']['firstName']));
        $this->applicant['name']['middleName'] = ucfirst(strtolower($this->applicant['name']['middleName']));
        $this->applicant['name']['lastName'] = ucwords(strtolower($this->applicant['name']['lastName']));
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

            $phoneObj = null;
            $contactNumber = null;
            $regionMode = config('app.region_mode');


            if (isset($event['parsedResume']['employee_contact'])) {
                $contacts = is_array($event['parsedResume']['employee_contact']) ? $event['parsedResume']['employee_contact'] : [$event['parsedResume']['employee_contact']];
                if ($regionMode == 'local') {

                    foreach ($contacts as $contact) {
                        try {
                            $phoneObj = new PhoneNumber($contact, 'PH');
                            $formattedContact = $phoneObj->formatNational();
                        } catch (\Exception $e) {
                            $formattedContact = $contact;
                        } finally {
                            $cleanedContact = preg_replace('/\D/', '', $formattedContact);
                            $contactNumbers[] = $cleanedContact;
                        }
                    }
                }

                $this->parsedResume['employee_contact'] = $contactNumbers;
                $this->applicant['mobileNumber'] = is_array($this->parsedResume['employee_contact']) ? end($this->parsedResume['employee_contact']) : $this->parsedResume['employee_contact'];
            }

            $this->updateParsedNameSegment();

            if (isset($event['parsedResume']['employee_email'])) {
                $this->applicant['email'] ??= is_array($this->parsedResume['employee_email']) ? end($this->parsedResume['employee_email']) : $this->parsedResume['employee_email'];
            }
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
            $name = $this->parsedResume['employee_name'];

            if (is_array($name)) {
                $name = implode(' ', $name);
            }

            $parsedFullName = preg_replace('/[^\p{L}\s.-]/u', '', $name);

            $parsedFullName = ucwords(strtolower($parsedFullName));
            $this->parsedNameSegment = array_unique(explode(' ', $parsedFullName));
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
