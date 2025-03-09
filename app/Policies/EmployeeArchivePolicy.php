<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeeArchivePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAnyArchivedRecords(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::VIEW_ARCHIVED_EMP_201_FILES)
            ? Response::allow()
            : Response::deny();
    }
}
