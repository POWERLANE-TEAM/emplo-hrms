<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IssuePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if user enployee permission to submit an issue report.
     */
    public function submitIssueReport(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::CREATE_ISSUE_REPORT)
            ? Response::allow()
            : Response::deny(__('Sorry, you don\'t have permission to file for an issue report.'));
    }

    /**
     * check if the issue report being access is owned by the user employee.
     */
    public function viewIssueReport(User $user, Issue $issue): Response
    {
        return $user->account->reportedIssues->contains($issue)
            ? Response::allow()
            : Response::deny(__('You don\'t own this issue report.'));
    }

    /**
     * Check if user employee can view any issue reported by any employee.
     */
    public function viewAnyIssueReport(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::VIEW_ALL_ISSUES)
            ? Response::allow()
            : Response::deny(__('Sorry, you don\'t have permission to access this issue report.'));
    }

    /**
     * Check if user employee can update (resolve / close) open issues.
     */
    public function updateIssueStatus(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::UPDATE_ISSUE_STATUS)
            ? Response::allow()
            : Response::deny(__('You lack permission to update issue reports status.'));
    }
}
