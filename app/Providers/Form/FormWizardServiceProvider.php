<?php

namespace App\Providers\Form;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Livewire\Applicant\Application\AdditionalDetailsStep;
use App\Livewire\Applicant\Application\ApplicationWizard;
use App\Livewire\Applicant\Application\FinalPreviewStep;
use App\Livewire\Applicant\Application\PersonalDetailsStep;
use App\Livewire\Applicant\Application\ResumeUploadStep;

class FormWizardServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        Livewire::component('form.applicant.application-wizard', ApplicationWizard::class);
        Livewire::component('form.applicant.resume-upload-step', ResumeUploadStep::class);
        Livewire::component('form.applicant.personal-details-step', PersonalDetailsStep::class);
        Livewire::component('form.applicant.additional-details-step', AdditionalDetailsStep::class);
        Livewire::component('form.applicant.final-preview-step', FinalPreviewStep::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
