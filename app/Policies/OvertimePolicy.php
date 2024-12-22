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
            ->whereNull('authorizer_signed_at')
            ->count();

        return $pendingRequestCount >= $this->allowedPendingRequestCount
            ? Response::deny(__('You may only have three (3) pending status of overtime requests at a time.'))
            : Response::allow();
    }

    /**
     * Check if user employee is an initial approver (supervisor) of overtime summary.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function approveOvertimeSummaryInitial(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_OVERTIME_SUMMARY_INITIAL)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user employee is a secondary approver (dept head/area manager) of overtime summary.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function approveOvertimeSummarySecondary(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_OVERTIME_SUMMARY_SECONDARY)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user employee is a third approver (hr staff/manager) of overtime summary.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function approveOvertimeSummaryTertiary(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_OVERTIME_SUMMARY_TERTIARY)
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
            UserPermission::VIEW_SUBORDINATE_OVERTIME_REQUEST,
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
    public function viewSubordinateOvertimeRequest(User $user): Response
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
        $record = $overtime->first();

        return is_null($record->authorizer_signed_at) &&
            is_null($record->denied_at)
                ? Response::allow()
                : Response::deny();
    }

    /**
     * Check if user employee can authrorize overtime request.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function authorizeOvertimeRequest(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::AUTHORIZE_OVERTIME_REQUEST)
            ? Response::allow()
            : Response::deny();
    }

    public function viewOvertimeSummary(User $user, Employee $employee): Response
    {
        $employee->load([
            'jobTitle.jobFamily',
            'jobTitle.department',
        ]);

        $user->load([
            'account.jobTitle.jobFamily',
            'account.jobTitle.department',
        ]);

        $userJobFamily      = $user->account->jobTitle->jobFamily;
        $userDepartment     = $user->account->jobTitle->department;
        $employeeJobFamily  = $employee->jobTitle->jobFamily;
        $employeeDepartment = $employee->jobTitle->department;

        // is user employee the supervisor of employee? Compare both JobFamily models
        if (
            $user->hasPermissionTo(UserPermission::VIEW_ALL_SUBORDINATE_OVERTIME_SUMMARY_FORMS) &&
            $userJobFamily->is($employeeJobFamily)
        ) {
            return Response::allow();
        }

        // is user employee the dept head/manager of employee? Compare both Department models
        if (
            $user->hasPermissionTo(UserPermission::VIEW_ALL_SUBORDINATE_REQUESTS) &&
            $userDepartment->is($employeeDepartment)
        ) {
            return Response::allow();
        }

        // // checks if user employee is like idk the HR Manager
        if ($user->hasPermissionTo(UserPermission::VIEW_ALL_OVERTIME_REQUEST)) {
            return Response::allow();
        }

        return Response::deny();
    }
}
