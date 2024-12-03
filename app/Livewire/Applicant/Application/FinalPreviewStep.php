<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\AccountType;
use App\Enums\CivilStatus;
use App\Enums\UserPermission;
use App\Http\Controllers\Application\ApplicantController;
use App\Models\JobVacancy;
use App\Traits\Applicant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\LivewireWizard\Components\StepComponent;

class FinalPreviewStep extends StepComponent
{
    use Applicant;

    public bool $isSubmitted = false;

    public $formState = null;

    public function mount()
    {
        if (Auth::check()) {
            if (self::applicantOrYet(!Auth::user()->hasPermissionTo(UserPermission::VIEW_JOB_APPLICATION_FORM->value), true));
            else self::hasApplication(true);
        } else abort(401);
    }


    public function boot()
    {

        // Get all form wizard steps states
        $this->formState = $this->state()->all();
    }

    public function save()
    {

        $resumePreviewSrc = $this->formState['form.applicant.resume-upload-step']['resumePath'] ?? null;
        $dPPreviewSrc = $this->formState['form.applicant.personal-details-step']['displayProfilePath'] ?? null;
        $personalDetails = $this->formState['form.applicant.personal-details-step'] ?? [];

        $applicantName = $personalDetails['form']['applicantName'];

        $parsedResumeData = $personalDetails['parsedResume'];


        $tempResumeFile = $this->transfromToFile($resumePreviewSrc);
        $tempDPFile = $this->transfromToFile($dPPreviewSrc);

        $applicant = $applicantName;

        $applicant =  array_merge($applicant, [
            'user' => [
                'photo' => $tempDPFile,
                'email' => $parsedResumeData['employee_email'] ?? null
            ],
            'application' => [
                'jobVacancyId' => JobVacancy::first()->job_vacancy_id,
            ],
            'resumeFile' => $tempResumeFile,
            'presentBarangay' => 14104,
            'presentAddress' => fake()->streetName(),
            'permanentBarangay' => 14104,
            'permanentAddress' => fake()->streetName(),
            'contactNumber' => $parsedResumeData['employee_contact'] ?? 'Not Set',
            'sex' => $personalDetails['sexAtBirth'],
            'civilStatus' => CivilStatus::SINGLE->value,
            'dateOfBirth' => $personalDetails['form']['applicantBirth'],
            'education' => $parsedResumeData['employee_education'],
            'experience' => $parsedResumeData['employee_experience'],
            'skills' => $parsedResumeData['employee_skills'],
        ]);

        $applcantController = new ApplicantController();


        $applcantController->store($applicant, true);
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
