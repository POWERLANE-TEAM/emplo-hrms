<?php

namespace App\Livewire\Applicant\Application;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\LivewireWizard\Components\WizardComponent;

class ApplicationWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            // Order Matters here
            ResumeUploadStep::class,
            PersonalDetailsStep::class,
            FinalPreviewStep::class,

        ];
    }
}
