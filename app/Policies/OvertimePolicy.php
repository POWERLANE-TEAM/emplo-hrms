<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use App\Enums\UserPermission;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Access\Response;

class OvertimePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct(
        private $dailyAllowedRequestCount = 1,
        private $allowedPendingRequestCount = 3
    ) {}

    /**
     * Check if user has permission whether inherited via roles or direct assignment.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function submitOvertimeRequest(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::CREATE_OVERTIME_REQUEST)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user can submit an overtime request today.
     * 
     * @param mixed $user
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Auth\Access\Response
     */
    public function submitOvertimeRequestToday(?User $user, Employee $employee): Response
    {
       $dailyRequestCount = $employee->overtimes()
            ->whereDate('filed_at', Carbon::today())
            ->count();

       return $dailyRequestCount >= $this->dailyAllowedRequestCount
            ? Response::deny(__('You may only submit an overtime request once a day.'))
            : Response::allow();
    }

    /**
     * Check if user has reached max allowed pending status of overtime request submissions.
     * 
     * @param mixed $user
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Auth\Access\Response
     */
    public function submitNewOrAnotherOvertimeRequest(?User $user, Employee $employee): Response
    {
        $pendingRequestCount = $employee->overtimes()
            ->whereHas('processes', function ($query) {
                $query->whereNull('hr_manager_approved_at');
            })
            ->count();

        return $pendingRequestCount >= $this->allowedPendingRequestCount
            ? Response::deny(__('You may only have three (3) pending status of overtime requests at a time.'))
            : Response::allow();
    }
}
