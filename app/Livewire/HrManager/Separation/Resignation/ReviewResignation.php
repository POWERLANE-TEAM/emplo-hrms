<?php

namespace App\Livewire\HrManager\Separation\Resignation;

use App\Enums\FilePath;
use App\Models\Resignation;
use App\Enums\ResignationStatus;
use App\Http\Controllers\Separation\ResignationController;
use Livewire\Component;

class ReviewResignation extends Component
{

    public Resignation $resignation;

    public string $approvalComment = '';

    public string $rejectionComment = '';

    public bool $hasResignation;

    public function mount()
    {
        $this->hasResignation = $this->resignation->resignationLetter->employee->documents()->where('file_path', 'like', '%' . FilePath::RESIGNATION->value . '%')->exists();

        if ($this->hasResignation) {
            // dd($this->resignation->resignee);
            $this->resignation->loadMissing('resigneeLifecycle','resignationLetter');
        }
    }

    private function save($approvalStatus)
    {

        $controller = new ResignationController();

        $controller->update([
            'resignation_id' => $this->resignation->resignation_id,
            'resignation_status_id' => $approvalStatus,
            'initial_approver_comments' => $approvalStatus === ResignationStatus::APPROVED->value ? $this->approvalComment : $this->rejectionComment,
        ], validated: true);

        $this->resignation->refresh();

    }

    public function saveApproval()
    {
        $this->save(ResignationStatus::APPROVED->value);

        $this->dispatch('show-toast', [
            'type' => 'success',
            'message' => 'Resignation approved successfully.',
        ]);

        $this->dispatch('changes-saved', ['modalId' => 'confirmApproval']);
    }

    public function saveRejection()
    {
        $this->save(ResignationStatus::REJECTED->value);

        $this->dispatch('show-toast', [
            'type' => 'danger',
            'message' => 'Resignation rejected.',
        ]);

        $this->dispatch('changes-saved', ['modalId' => 'confirmRejection']);
    }

    public function render()
    {
        return view('livewire.hr-manager.separation.resignation.review-resignation');
    }
}
