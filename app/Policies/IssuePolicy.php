<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserPermission;
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

    public function submitIssueReport(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::CREATE_ISSUE_COMPLAINT)
            ? Response::allow()
            : Response::deny(__('Sorry, you don\'t have permission to file for a leave.'));
    }
}
