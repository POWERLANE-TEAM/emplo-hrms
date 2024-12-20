<?php

namespace App\Livewire\Applicant\Stages;

use App\Models\Application;
use App\Models\ApplicationDoc;
use Livewire\Component;

class PreEmployed extends Component
{
    public bool $isMissingAssement;

    public Application $application;

    protected /* ApplicationDoc */ $pendingDocuments;

    protected /* ApplicationDoc */ $verifiedDocuments;

    protected /* ApplicationDoc */ $rejectedDocuments;

    public function mount(Application $application, $isMissingAssement)
    {
        $this->application = $application->load('documents');
        $this->isMissingAssement = $isMissingAssement;
    }

    public function boot()
    {
        $this->application->load('documents');
        $this->fetchDocumentStatus();
    }

    public function fetchDocumentStatus()
    {
        $this->pendingDocuments = $this->application->documents->where('evaluated_by', null);
        $this->verifiedDocuments = $this->application->documents->whereNotNull('evaluated_by');
        $this->rejectedDocuments = $this->application->documents->whereNotNull('evaluated_by');
    }


    public function render()
    {
        return view('livewire.applicant.stages.pre-employed', [
            'pendingDocuments' => $this->pendingDocuments,
            'verifiedDocuments' => $this->verifiedDocuments,
            'rejectedDocuments' => $this->rejectedDocuments,
        ]);
    }
}
