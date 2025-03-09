<?php

namespace App\Livewire\Employee\Leaves;

use App\Enums\StatusBadge;
use App\Enums\UserPermission;
use App\Models\EmployeeLeave;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class RequestorInfo extends Component
{
    use WithPagination;

    public EmployeeLeave $leave;

    #[Locked]
    public $routePrefix;

    #[Locked]
    public $status;

    #[Locked]
    public $previousLeaveId;

    #[Locked]
    public $nextLeaveId;

    public function mount()
    {
        $this->leave->loadMissing([
            'employee.account',
            'employee.jobDetail',
            'employee.jobTitle.jobFamily',
        ]);
    }

    private function getLeaveRequestStatus()
    {
        $this->status = match (true) {
            ! is_null($this->leave->denied_at) => StatusBadge::DENIED,
            ! is_null($this->leave->fourth_approver_signed_at) => StatusBadge::APPROVED,
            default => StatusBadge::PENDING,
        };
    }

    public function getRemainingLeaves()
    {
        $higherPermissions = [
            UserPermission::APPROVE_LEAVE_REQUEST_THIRD,
            UserPermission::APPROVE_LEAVE_REQUEST_FOURTH,
        ];

        $query = EmployeeLeave::query()
            ->when($this->user->hasAnyPermission($higherPermissions),
                function ($query) {
                    return $query->whereNotNull('third_approver_signed_at')
                        ->orWhereNotNull('secondary_approver_signed_at');
                }
            )
            ->when(! $this->user->hasAnyPermission($higherPermissions),
                function ($query) {
                    return $query->whereHas('employee.jobTitle.jobFamily', function ($subquery) {
                        $subquery->where('job_family_id', $this->user->account->jobTitle->jobFamily->job_family_id);
                    });
                }
            )
            ->orderBy('emp_leave_id', 'asc')
            ->get();

        $count = $query->count();
        $remaining = $query->paginate($count);

        if ($query->isNotEmpty()) {
            $currentIndex = $query->search(fn ($item) => $item->emp_leave_id === $this->leave->emp_leave_id);

            $this->previousLeaveId = ($currentIndex > 0) ? $query[$currentIndex - 1]->emp_leave_id : null;
            $this->nextLeaveId = ($currentIndex < $count - 1) ? $query[$currentIndex + 1]->emp_leave_id : null;
        }

        return $remaining;
    }

    #[Computed]
    public function user()
    {
        return Auth::user();
    }

    #[On('leaveRequestApproved')]
    public function render()
    {
        // $this->getRemainingLeaves();
        $this->getLeaveRequestStatus();

        return view('livewire.employee.leaves.requestor-info');
    }
}
