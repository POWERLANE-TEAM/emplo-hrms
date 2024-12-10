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

        $applicantName = $personalDetails['applicant']['name'];

        $parsedResumeData = $personalDetails['parsedResume'];


        $tempResumeFile = $this->transfromToFile($resumePreviewSrc);
        $tempDPFile = $this->transfromToFile($dPPreviewSrc);

        $applicant = $applicantName;

        // I get the education as a whole string as is from resume
        // I have no idea how to separate education tho
        $education = $parsedResumeData['employee_education'] ?? null;

        if (is_string($education)) {
            // Log the original education string
            Log::info('Original education string', ['education' => $education]);

            // Define a regex pattern to match date formats (e.g., "August 2024", "June 2014 - June 2019")
            $datePattern = '/
            (?:^|\n)                                       # Start of the string or a newline
            (January|February|March|April|May|June|July|August|September|October|November|December)
            (\s+\d{4})                                     # Match month and year
            (\s*-\s*
            ((January|February|March|April|May|June|July|August|September|October|November|December)
            \s+\d{4}|Present))?                            # Optionally match a date range or "Present"
        /ix';

            // Split the education string based on the date pattern
            $educationArray = preg_split($datePattern, $education, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            // Log the split education array
            Log::info('Split education array', ['educationArray' => $educationArray]);

            // Combine dates with their corresponding entries
            $parsedEducation = [];
            $currentEntry = null;

            foreach ($educationArray as $value) {
                $value = trim($value);
                if (preg_match($datePattern, $value)) {
                    // Start a new entry with the date
                    if ($currentEntry) {
                        $parsedEducation[] = $currentEntry;
                    }
                    $currentEntry = ['date' => $value, 'details' => ''];
                } else {
                    // Append details to the current entry
                    if ($currentEntry) {
                        $currentEntry['details'] .= ($currentEntry['details'] ? "\n" : '') . $value;
                    }
                }
            }

            // Add the last entry
            if ($currentEntry) {
                $parsedEducation[] = $currentEntry;
            }

            // Log the parsed education entries
            Log::info('Parsed education entries', ['education' => $parsedEducation]);

            $education = $parsedEducation;
        }

        $experience = $parsedResumeData['employee_experience'] ?? null;
        $skills = $parsedResumeData['employee_skills'] ?? null;

        $applicant =  array_merge($applicant, [
            'user' => [
                'photo' => $tempDPFile,
                'email' => $personalDetails['applicant']['email'] ?? null
            ],
            'application' => [
                'jobVacancyId' => JobVacancy::first()->job_vacancy_id,
            ],
            'resumeFile' => $tempResumeFile,
            'presentBarangay' => 14104,
            'presentAddress' => fake()->streetName(),
            'permanentBarangay' => 14104,
            'permanentAddress' => fake()->streetName(),
            'contactNumber' => $personalDetails['applicant']['mobileNumber'] ?? 'Not Set',
            'sex' => $personalDetails['sexAtBirth'],
            'civilStatus' => CivilStatus::SINGLE->value,
            'dateOfBirth' => $personalDetails['applicant']['birth'],
            'education' => $education,
            'experience' => $experience,
            'skills' => $skills,
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
