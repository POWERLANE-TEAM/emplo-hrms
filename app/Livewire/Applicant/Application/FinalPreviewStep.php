<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\AccountType;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\LivewireWizard\Components\StepComponent;

class FinalPreviewStep extends StepComponent
{

    public bool $isSubmitted = false;

    public $formState = null;

    public function mount()
    {
        if (Auth::check()) {

            if (Auth::user()->account_type == AccountType::EMPLOYEE->value) {
                abort(403);
            }
        } else {
            // abort(401);
        }
    }


    public function boot()
    {

        Log::info('FinalPreviewStep boot', ['state' => $this->state()->all()]);
        $this->formState = $this->state()->all();
    }

    public function save()
    {
        $stateAll = $this->state()->all();
        dump($stateAll);

        $resumePreviewSrc = $this->formState['form.applicant.resume-upload-step']['resumePath'] ?? null;
        $dPPreviewSrc = $this->formState['form.applicant.personal-details-step']['displayProfilePath'] ?? null;
        $personalDetails = $this->formState['form.applicant.personal-details-step'] ?? [];

        $applicantName = $personalDetails['form']['applicantName'];

        dump($personalDetails);

        $tempResumeFile = $this->transfromToFile($resumePreviewSrc);
        $tempDPFile = $this->transfromToFile($dPPreviewSrc);

        $applicant = $applicantName;

        dump($applicant);
        $applicant =  array_merge($applicant, [
            'user' => [
                'photo' => $tempDPFile
            ],
            'application' => [
                'applicantId' => null,
                'jobVacancyId' => null
            ],
            'resumeFile' => $tempResumeFile,
            'presentBarangay' => null,
            'presentAddress' => null,
            'permanentBarangay' => null,
            'permanentAddress' => null,
            'contactNumber' => null,
            'sex' => $personalDetails['sexAtBirth'],
            'civilStatus' => null,
            'dateOfBirth' => $personalDetails['form']['applicantBirth'],
            'education' => null,
            'experience' => null,
        ]);


        dump($applicant);
    }

    public function stepInfo(): array
    {
        return [
            'title' => 'Final Information',
            // tags if complete and fields were valid
            'isComplete' => $this->isSubmitted,
        ];
    }

    public function  transfromToFile($filePath)
    {
        try {
            if (file_exists($filePath)) {

                $tempFile = new \Illuminate\Http\UploadedFile(
                    $filePath,
                    basename($filePath),
                    null,
                    null,
                    true
                );

                return $tempFile;
            }
        } catch (\Throwable $th) {
            report($th);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('livewire.applicant.application.final-preview-step', [
            'formState' => $this->formState,
        ]);
    }
}
