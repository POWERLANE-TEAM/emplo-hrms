<?php

namespace App\Policies;

use App\Models\Overtime;
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
     * Check if user owns the overtime model request before updating.
     * 
     * @param mixed $user
     * @param \App\Models\Employee $employee
     * @param \App\Models\Overtime $overtime
     * @return \Illuminate\Auth\Access\Response
     */
    public function updateOvertimeRequest(?User $user, Employee $employee, Overtime $overtime): Response
    {
        return $employee->overtimes->contains($overtime)
            ? Response::allow()
            : Response::deny(__('You don\'t own this overtime request.'));
    }

    /**
     * Check if user employee can submit an overtime request today.
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
     * Check if user employee has reached max allowed pending status of overtime request submissions.
     * 
     * @param mixed $user
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Auth\Access\Response
     */
    public function submitNewOrAnotherOvertimeRequest(?User $user, Employee $employee): Response
    {
        $pendingRequestCount = $employee->overtimes()
            ->whereHas('processes', function ($query) {
                $query->whereNull('secondary_approver_signed_at');
            })
            ->count();

        return $pendingRequestCount >= $this->allowedPendingRequestCount
            ? Response::deny(__('You may only have three (3) pending status of overtime requests at a time.'))
            : Response::allow();
    }

    /**
     * Check if user employee is an initial approver (supervisor/dept head) of overtime request submissions.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function updateSubordinateOvertimeRequest(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::UPDATE_SUBORDINATE_OVERTIME_REQUEST)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user employee can see all overtime requests, regardless of job family.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function viewOvertimeRequestAsSecondaryApprover(User $user): Response
    {
        return $user->hasAllPermissions([
            UserPermission::VIEW_SUBORDINATE_LEAVE_REQUEST,
            UserPermission::VIEW_ALL_OVERTIME_REQUEST,
        ]) || $user->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME_REQUEST)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user can see overtime requests under his jurisdiction.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function viewOvertimeRequestAsInitialApprover(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::VIEW_SUBORDINATE_OVERTIME_REQUEST)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user employee is a secondary approver (hr staff/manager) of overtime request submissions.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function updateAllOvertimeRequest(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::UPDATE_ALL_OVERTIME_REQUEST)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user employee overtime request is still untouched by approvers.
     * 
     * @param \App\Models\Overtime $overtime
     * @return \Illuminate\Auth\Access\Response
     */
    public function editOvertimeRequest(?User $user, Overtime $overtime): Response
    {
        $process = $overtime->processes->first();

        return is_null($process->initial_approver_signed_at) &&
            is_null($process->secondary_approver_signed_at) &&
            is_null($process->denied_at)
                ? Response::allow()
                : Response::deny();
    }
}
