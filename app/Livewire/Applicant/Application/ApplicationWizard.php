<?php

namespace App\Livewire\Applicant\Application;

use App\Enums\UserPermission;
use App\Models\JobVacancy;
use App\Traits\Applicant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\LivewireWizard\Components\WizardComponent;

class ApplicationWizard extends WizardComponent
{
    use Applicant;

    public JobVacancy $jobVacancy;

    public function mount(JobVacancy $jobVacancy): void
    {
        $this->jobVacancy = $jobVacancy;
    }


    public function boot()
    {
        if (auth()->check()) {
            // check if applicant or guest and if employee has permission to view job application form
            if (self::applicantOrYet(!auth()->user()->hasPermissionTo(UserPermission::VIEW_JOB_APPLICATION_FORM->value), true));
            else self::hasApplication(true);
        } else abort(401);
    }

    public function steps(): array
    {
        return [
            // Order Matters here
            AdditionalDetailsStep::class,
            PersonalDetailsStep::class,
            ResumeUploadStep::class,
            FinalPreviewStep::class,

        ];
    }

    public function initialState(): array
    {
        return [
            // 'form.applicant.resume-upload-step' => [
            //     'jobVacancy' => $this->jobVacancy,
            // ],
            'form.applicant.final-preview-step' => [
                'jobVacancy' => $this->jobVacancy,
            ],
        ];
    }
}
