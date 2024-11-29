<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\AccountType;
use Closure;
use Illuminate\Contracts\View\View;
use Spatie\LivewireWizard\Components\StepComponent;
use App\Livewire\Forms\FileForm;
use App\Traits\HasObjectForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Locked;
use Spatie\LivewireFilepond\WithFilePond;

class ResumeUploadStep extends StepComponent
{
    use WithFilePond, HasObjectForm;

    public $resume;

    public $resumeFile;

    public $resumeUrl;

    public $resumePath;

    #[Locked]
    public $fileMaxSize;

    public bool $isValid = false;


    public function boot()
    {
        if (Auth::check()) {

            if (Auth::user()->account_type == AccountType::EMPLOYEE->value) {
                abort(403);
            }
        } else {
            // abort(401);
        }

        // https://github.com/spatie/laravel-livewire-wizard/discussions/85 but this is not applicable to my case
        // My work around as $resume is not being rehydrated properly by Livewire
        // For example if the user goes back to the previous step and then comes back to this step
        // public FileForm $resume;
        //  it will throw Cannot assign array to property App\View\Components\Applicant\Application\ResumeUploadStep::$resume of type App\Livewire\Forms
        // $this->resume = new FileForm($this, $this->getName());

        // Log::info('ResumeUploadStep boot', ['state' => $this->state()->all()]);

        $this->mountWithObjectForm(FileForm::class, 'resume');

        if (isset($this->resumeUrl)) {
            $this->resumeFile = $this->resumeUrl;
        }

        $acceptedFileTypes = ['pdf'];
        $maxSize = 'xs';
        $required = true;

        $this->resume->setAccepted($acceptedFileTypes);
        $this->fileMaxSize = $this->resume->setMaxSize($maxSize);
        $this->resume->setRequired($required);
    }

    public function validateNow()
    {

        // $this->rehydrateFile($this->resumePath, $this->resumeFile);

        if (isset($this->resumePath)) {
            if (file_exists($this->resumePath)) {
                $tempFile = new \Illuminate\Http\UploadedFile(
                    $this->resumePath,
                    basename($this->resumePath),
                    null,
                    null,
                    true
                );

                $this->resumeFile = $tempFile;
            } else {
                Log::error("File does not exist: " . $this->resumePath);
                $this->resumeFile = null;
            }
        }

        $this->resume->file = $this->resumeFile;

        $this->isValid = false;

        $this->validate();

        $this->isValid = true;

        $this->nextStep();
    }

    public function stepInfo(): array
    {
        return [
            'title' => 'Upload Resume',
            // tags if complete and fields were valid
            'isComplete' => $this->isValid,
        ];
    }

    public function updated()
    {

        // $this->rehydrateFileLink($this->resumeFile, $this->resumeUrl, $this->resumePath);

        if (isset($this->resumeFile) && $this->resumeFile instanceof \Illuminate\Http\UploadedFile) {
            try {
                $this->resumeUrl = $this->resumeFile->temporaryUrl();
                $this->resumePath = $this->resumeFile->getRealPath();

                // Log::info('Display Profile Path', ['path' => $this->resumePath]);
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        return view('livewire.applicant.application.resume-upload-step');
    }
}
