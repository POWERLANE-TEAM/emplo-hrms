<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\AccountType;
use App\Enums\Sex;
use App\Http\Controllers\DocumentController;
use App\Livewire\Forms\DateForm;
use App\Livewire\Forms\FileForm;
use App\Livewire\Forms\PersonNameForm;
use App\Traits\HasObjectForm;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Computed;
use Spatie\LivewireFilepond\WithFilePond;
use Spatie\LivewireWizard\Components\StepComponent;

class PersonalDetailsStep extends StepComponent
{

    use WithFilePond, HasObjectForm;

    public $displayProfile;

    public $applicantName;

    public $applicantBirth;

    public ?string $sexAtBirth;

    public $form = [
        'displayProfile' => null,
        'applicantName' => ['firstName' => '', 'middleName' => '', 'lastName' => ''],
        'applicantBirth' => '',
    ];

    public $displayProfileUrl;

    public $resumeUrl;

    public $resumePath;

    public $displayProfilePath;

    public ?array $parsedResume = null;

    public bool $isValid = false;

    public function mount(?array $parsedResume = null)
    {

        if (Auth::check()) {
            if (Auth::user()->account_type == AccountType::EMPLOYEE->value) {
                abort(403);
            }
        } else {
            // abort(401);
        }
        // $this->parsedResume = $parsedResume;

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

        // dump($this->parsedResume);
        // dump($this->resumePath);

        // add check if user approves consent to parse resume
        if (true &&  empty($this->parsedResume) && isset($this->resumePath)) {
            try {
                $resumeParser = new DocumentController();

                $resumeFile = new \Illuminate\Http\UploadedFile(
                    $this->resumePath,
                    basename($this->resumePath),
                    null,
                    null,
                    true
                );

                $this->parsedResume = $resumeParser->recognizeText($resumeFile, 'array');

                dump($this->parsedResume);
            } catch (\Throwable $th) {
                report($th);
            }
        }


        if (empty($this->parsedResume)) {
            $this->parsedResume = [
                'employee_education' => 'Bachelor of Arts in Communication, Ateneo de Manila University',
                'employee_contact' => '+63-961-5719',
                'employee_email' => 'fernando.poe.jr.@samplemail.com',
                'employee_experience' => 'Globe Telecom - 3 years as Project Manager\nRobinsons Land - 5 years as Marketing Specialist',
                'employee_name' => 'Grace, Fernando Poe Jr.',
                'employee_skills' => 'Project Management, Customer Service, Problem Solving',
            ];
        }

        // dump($resumeState);
    }

    public function boot()
    {

        // Log::info('PersonalDetailsStep boot', ['state' => $this->state()->all()]);
        // Copied fix here  https://github.com/spatie/laravel-livewire-wizard/discussions/100#discussioncomment-10125903
        // still broken
        $this->mountWithObjectForm(FileForm::class, 'displayProfile');
        $this->mountWithObjectForm(PersonNameForm::class, 'applicantName');
        $this->mountWithObjectForm(DateForm::class, 'applicantBirth');

        if (isset($this->displayProfileUrl)) {

            // I cant rehydrate the file object properly
            // it become blank array
            $this->form['displayProfile'] = $this->displayProfileUrl;
        }

        $this->applicantName->firstName = $this->form['applicantName']['firstName'];
        $this->applicantName->middleName = $this->form['applicantName']['middleName'];
        $this->applicantName->lastName = $this->form['applicantName']['lastName'];

        $maxSize = 'xs';
        $required = true;

        $this->displayProfile->setToImageMode();
        $this->displayProfile->setMaxSize($maxSize);
        $this->displayProfile->setRequired($required);

        // atleast 18 years old
        $this->applicantBirth->setMinDate(Carbon::now()->subYears(18));

        // at most 65 years old
        $this->applicantBirth->setMaxDate(Carbon::now()->addYears(65));
    }

    #[Computed]
    protected function rules()
    {
        return [
            'sexAtBirth' => 'required|in:' . implode(',', array_keys(Sex::options())),
        ];
    }

    public function validateNow()
    {

        // Make a file object first
        if (isset($this->displayProfilePath)) {

            if (file_exists($this->displayProfilePath)) {

                $tempFile = new \Illuminate\Http\UploadedFile(
                    $this->displayProfilePath,
                    basename($this->displayProfilePath),
                    null,
                    null,
                    true
                );


                $this->form['displayProfile'] = $tempFile;
            } else {
                Log::error("File does not exist: " . $this->displayProfilePath);

                $this->form['displayProfile'] = null;
            }
        }

        $this->displayProfile->file = $this->form['displayProfile'];

        $this->applicantName->firstName = $this->form['applicantName']['firstName'];
        $this->applicantName->middleName = $this->form['applicantName']['middleName'];
        $this->applicantName->lastName = $this->form['applicantName']['lastName'];

        $this->applicantBirth->date = $this->form['applicantBirth'];

        $this->isValid = false;

        $this->validate();

        $this->isValid = true;

        $this->nextStep();
        // dump($this->applicantName);
    }

    public function stepInfo(): array
    {
        return [
            'title' => 'Personal Details',
            // tags if complete and fields were valid
            'isComplete' => $this->isValid,
        ];
    }


    public function updated()
    {
        // save the file properties needed that is persistent when rehydrating
        if (isset($this->form['displayProfile']) && $this->form['displayProfile'] instanceof \Illuminate\Http\UploadedFile) {
            try {
                $this->displayProfileUrl = $this->form['displayProfile']->temporaryUrl();
                $this->displayProfilePath = $this->form['displayProfile']->getRealPath();

                // Log::info('Display Profile Path', ['path' => $this->displayProfilePath]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
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
