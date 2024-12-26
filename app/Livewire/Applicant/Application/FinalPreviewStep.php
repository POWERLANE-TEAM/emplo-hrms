<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\AccountType;
use App\Enums\CivilStatus;
use App\Enums\UserPermission;
use App\Http\Controllers\Application\ApplicantController;
use App\Models\Barangay;
use App\Models\City;
use App\Models\JobVacancy;
use App\Models\Province;
use App\Models\Region;
use App\Traits\Applicant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Spatie\LivewireWizard\Components\StepComponent;

class FinalPreviewStep extends StepComponent
{

    public bool $isSubmitted = false;

    public $formState = null;

    protected $applicationForm = null;

    protected JobVacancy $jobVacancy;


    public function boot()
    {
        try {
            $this->formState = $this->state()->all();
            // dump($this->formState);
            // dump($this->state()->currentStep());
            // dump($this->state()->forStep('form.applicant.final-preview-step')['formState']['form.applicant.final-preview-step']['allStepsState']['form.applicant.final-preview-step']['jobVacancy']);
            // // $this->jobVacancy = $this->formState['form.applicant.final-preview-step']['jobVacancy'];
            // // dump($this->jobVacancy);
            // dump($this->formState['form.applicant.final-preview-step']["allStepsState"]["form.applicant.final-preview-step"]["jobVacancy"]);
            // dd($this->formState['form.applicant.final-preview-step']);

            $this->jobVacancy = $this->formState['form.applicant.final-preview-step']["allStepsState"]["form.applicant.final-preview-step"]["jobVacancy"];
            $resumePreviewSrc = $this->formState['form.applicant.resume-upload-step']['resumePath'] ?? null;
            $profilePhotoSrc = $this->formState['form.applicant.personal-details-step']['displayProfilePath'] ?? null;
            $personalDetails = $this->formState['form.applicant.personal-details-step'] ?? [];
            $additionalDetails = $this->formState['form.applicant.additional-details-step'] ?? null;

            $applicantName = $personalDetails['applicant']['name'];

            $parsedResumeData = $personalDetails['parsedResume'];

            $tempResumeFile = $this->transfromToFile($resumePreviewSrc);
            $tempProfileFile = $this->transfromToFile($profilePhotoSrc);

            $applicant = $applicantName;

            $address = $additionalDetails['address'] ?? null;

            // I get the education as a whole string as is from resume
            // I have no idea how to separate education tho
            $education = $parsedResumeData['employee_education'] ?? null;

            if (is_string($education)) {
                $education = [$education];
            }

            $experience = $parsedResumeData['employee_experience'] ?? null;
            if (is_string($experience)) {
                $experience = [$experience];
            }

            $skills = $parsedResumeData['employee_skills'] ?? null;
            if (is_string($skills)) {
                $skills = [$skills];
            }

            $applicant = array_merge($applicant, [
                'presentBarangay' => $address['presentBarangay'] ?? null,
                'presentAddress' => $address['presentAddress'] ?? null,
                'permanentBarangay' => $address['permanentBarangay'] ?? null,
                'permanentAddress' => $address['permanentAddress'] ?? null,
            ]);

            $applicant =  array_merge($applicant, [
                'user' => [
                    'photo' => $tempProfileFile,
                    'email' => $personalDetails['applicant']['email'] ?? auth()->user()->email ?? null,
                ],
                'application' => [
                    'jobVacancyId' =>  $this->jobVacancy->job_vacancy_id,
                ],
                'resumeFile' => $tempResumeFile,
                'contactNumber' => $personalDetails['applicant']['mobileNumber'] ?? null,
                'sex' => $personalDetails['sexAtBirth'] ?? null,
                'civilStatus' => $additionalDetails['civilStatus'] ?? null,
                'dateOfBirth' => $personalDetails['applicant']['birth'] ?? null,
                'education' => $education,
                'experience' => $experience,
                'skills' => $skills,
            ]);

            // dump($applicant);

            $this->applicationForm = $applicant;
        } catch (\Throwable $th) {
            report($th);
        }
    }

    public function save()
    {

        $applcantController = new ApplicantController();

        $applcantController->store($this->applicationForm, true);

        $this->dispatch('applicant-application-received');
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


    public function getAddress(string $type)
    {
        if (!in_array($type, ['present', 'permanent'])) {
            throw new \Exception('Invalid address type');
        }

        try {
            // $fromBarangay = Barangay::where('id', $this->applicationForm[$type . 'Barangay'])
            //     ->with(['region', 'province', 'city'])
            //     ->first();

            $query = Barangay::where('id', $this->applicationForm[$type . 'Barangay'])
                ->with(['region', 'province', 'city']);

            $sql = $query->toSql();
            $bindings = $query->getBindings();
            $fromBarangay
                = $query->first();
            // dump($sql, $bindings);
            // dump($fromBarangay);

            $region = $fromBarangay->region->name;
            $province = $fromBarangay->province->name;
            $city = $fromBarangay->city->name;
            $barangay = $fromBarangay->name;
            $addressString = $this->applicationForm[$type . 'Address'];

            $addressComponents = [$addressString, $barangay, $city, $province, $region];
            $address = implode(', ', $addressComponents);
        } catch (\Throwable $th) {
            $address = null;
            report($th);
        }

        return $address;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        // dd($this->applicationForm);
        return view('livewire.applicant.application.final-preview-step', [
            'formState' => $this->formState,
            'applicationForm' => $this->applicationForm,
        ]);
    }
}
