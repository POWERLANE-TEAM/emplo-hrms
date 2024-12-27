<?php

namespace App\Policies;

use App\Models\EmployeeLeave;
use App\Models\User;
use App\Enums\UserPermission;
use Illuminate\Auth\Access\Response;

class EmployeeLeavePolicy
{
    /** 
     * Create a new policy instance.
     */
    public function __construct(
        private $allowedPendingRequestCount = 5,
    ) {}

    /**
     * Check if user can file for leave request.
     * 
     * Should have direct or via role permission and not exceeding the allowed count of filed requests of pending status.
     * 
     * @param \App\Models\User $user
     * @return Response
     */
    public function fileLeaveRequest(User $user): Response
    {
        $pendingLeaveRequestCount = $user->account->leaves()
            ->whereNull('fourth_approver_signed_at')
            ->whereNull('denied_at')
            ->count();

        if (! $user->hasPermissionTo(UserPermission::CREATE_OVERTIME_REQUEST)) {
            return Response::deny(__('Sorry, you don\'t have permission to file for a leave.'));
        }

        if ($pendingLeaveRequestCount >= $this->allowedPendingRequestCount) {
            return Response::deny(__("You may only have {$this->allowedPendingRequestCount} pending status of overtime requests at a time."));
        }

        return Response::allow();
    }

    /**
     * Check if auth user employee owns one of the leave models he's trying to access.
     * 
     * @param \App\Models\User $user
     * @param \App\Models\EmployeeLeave $leave
     * @return Response
     */
    public function viewLeaveRequest(User $user, EmployeeLeave $leave): Response
    {
        return $user->account->leaves->contains($leave)
            ? Response::allow()
            : Response::deny(__('You don\'t own this leave request.'));
    }

    /**
     * Check if leave model is yet to have initial approval and denied status.
     * 
     * @param \App\Models\User $user
     * @param \App\Models\EmployeeLeave $leave
     * @return \Illuminate\Auth\Access\Response
     */
    public function updateLeaveRequest(?User $user, EmployeeLeave $leave): Response
    {
        return is_null($leave->initial_approver_signed_at) &&
            is_null($leave->denied_at)
                ? Response::allow()
                : Response::deny();
    }

    /**
     * Check if user employee can access a leave request thru permissions and model comparison.
     * 
     * @param \App\Models\User $user
     * @param \App\Models\EmployeeLeave $leave
     * @return Response
     */
    public function viewSubordinateLeaveRequest(User $user, EmployeeLeave $leave): Response
    {
        $requestorJobFamily = $leave->employee->jobTitle->jobFamily;
        $userJobFamily = $user->account->jobTitle->jobFamily;

        if (! $user->hasAnyPermission([
            UserPermission::VIEW_SUBORDINATE_LEAVE_REQUEST,
            UserPermission::VIEW_ALL_SUBORDINATE_REQUESTS
        ])) {
            return Response::deny(__('You don\'t have the necessary permissions to access this leave request.'));
        }

        if (! $userJobFamily->is($requestorJobFamily)) {
            return Response::deny(__('You are trying to access a forbidden resource.'));
        }

        return Response::allow();
    }

    public function approveSubordinateLeaveRequest(User $user): Response
    {
        return $user->hasAnyPermission([
            UserPermission::APPROVE_LEAVE_REQUEST_FIRST, 
            UserPermission::APPROVE_LEAVE_REQUEST_SECOND
        ])
            ? Response::allow()
            : Response::deny(__('You don\'t have the necessary permissions to access this leave request.'));
    }

    public function approveAnyLeaveRequest(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_LEAVE_REQUEST_THIRD)
            ? Response::allow()
            : Response::deny(__('You don\'t have the necessary permissions to access this leave request.'));
    }

    public function approveLeaveRequestFinal(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_LEAVE_REQUEST_FOURTH)
            ? Response::allow()
            : Response::deny(__('You don\'t have the necessary permissions to access this leave request.'));
    }
}
