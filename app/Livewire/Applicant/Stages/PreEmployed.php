<?php

namespace App\Livewire\Applicant\Stages;

use App\Models\Application;
use App\Models\ApplicationDoc;
use App\Models\PreempRequirement;
use Livewire\Attributes\Computed;
use Livewire\Component;

class PreEmployed extends Component
{
    public bool $isMissingAssement;

    public Application $application;

    protected /* ApplicationDoc */ $pendingDocuments;

    protected /* ApplicationDoc */ $verifiedDocuments;

    protected /* ApplicationDoc */ $rejectedDocuments;

    public function mount()
    {
        $this->application->load('documents');
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

        $this->dataChecks();
    }


    public function dataChecks()
    {
        if (($this->pendingDocuments->count() + $this->pendingDocuments->count() + $this->pendingDocuments->count()) >  $this->premploymentRequirements->count()) {
            report('Document count is greater than the pre-employment requirements count');
        }
    }


    /**
     * Computes the overall status of the applicant based on the document verification process.
     *
     * @return array An array containing the status and its type:
     *               - ['pending', 'info'] if all documents are pending.
     *               - ['complete', 'success'] if all documents are verified.
     *               - ['incomplete', 'danger'] if some documents are pending and some are verified.
     */
    #[Computed(persist: true)]
    public function overallStatus()
    {
        if ($this->pendingDocuments->count() == $this->premploymentRequirements->count()) {
            return [
                'pending',
                'info'
            ];
        } elseif ($this->verifiedDocuments->count() == $this->premploymentRequirements->count()) {
            return [
                'complete',
                'success'
            ];
        } else {
            return [
                'incomplete',
                'danger'
            ];
        }
    }

    #[Computed(persist: true)]
    public function premploymentRequirements()
    {
        return PreempRequirement::select('preemp_req_name')
        ->where('preemp_req_name', '!=', 'Resume')
        ->where('preemp_req_name', '!=', 'resume')
        ->get();
    }


    public function render()
    {
        return view('livewire.applicant.stages.pre-employed', [
            'pendingDocuments' => $this->pendingDocuments,
            'verifiedDocuments' => $this->verifiedDocuments,
            'rejectedDocuments' => $this->rejectedDocuments,
            'overallStatus' => $this->overallStatus,
        ]);
    }
}
