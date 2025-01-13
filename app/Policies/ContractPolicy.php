<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\UserPermission;
use Illuminate\Auth\Access\Response;

class ContractPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if user has permission to upload employee contract.
     * 
     * @param \App\Models\User $user
     * @return Response
     */
    public function uploadContractAttachment(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::UPLOAD_EMPLOYEE_CONTRACT)
            ? Response::allow()
            : Response::deny();
    }
}
