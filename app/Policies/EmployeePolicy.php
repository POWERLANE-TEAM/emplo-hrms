<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmployeePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if user can create employee account.
     * 
     * @param \App\Models\User $user
     * @return Response
     */
    public function createEmployeeAccount(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::CREATE_EMPLOYEE_ACCOUNT)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user can view employee list.
     * 
     * @param \App\Models\User $user
     * @return Response
     */
    public function viewAnyEmployees(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::VIEW_ALL_EMPLOYEES)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user can view specific employee information.
     * 
     * @param \App\Models\User $user
     * @return Response
     */
    public function viewEmployee(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::VIEW_EMPLOYEE_INFORMATION)
            ? Response::allow()
            : Response::deny();
    }
}
