<?php

namespace App\Policies;

use App\Enums\UserStatus;
use App\Models\User;

class UserStatusPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function isAccountActive(User $user): bool
    {
        return $user->status->user_status_id === UserStatus::ACTIVE->value;
    }
}
