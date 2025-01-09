<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\AccountType;
use App\Enums\UserPermission;
use Closure;
use Illuminate\Contracts\View\View;
use Spatie\LivewireWizard\Components\StepComponent;
use App\Livewire\Forms\FileForm;
use App\Models\JobVacancy;
use App\Traits\Applicant;
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

    protected JobVacancy $jobVacancy;


    public function boot()
    {

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

        if (isset($this->resumePath)) {

            try {
                if (file_exists($this->resumePath)) {
                    $tempFile = new \Illuminate\Http\UploadedFile(
                        $this->resumePath,
                        basename($this->resumePath),
                        null,
                        null,
                        true
                    );

                    $this->resumeFile = $tempFile;
                } else throw new \Exception($this->resumeFile . " not found");
            } catch (\Throwable $th) {
                report("Resume is lost while applicant is filling form" . $th);
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

        if (isset($this->resumeFile) && $this->resumeFile instanceof \Illuminate\Http\UploadedFile) {
            try {
                $this->resumeUrl = $this->resumeFile->temporaryUrl();
                $this->resumePath = $this->resumeFile->getRealPath();
            } catch (\Exception $e) {
                report($e->getMessage());
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
