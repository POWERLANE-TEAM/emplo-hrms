<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\Training;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TrainingPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if user can create training record.
     */
    public function createTrainingRecord(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::CREATE_EMPLOYEE_TRAINING)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user employee owns the training records.
     */
    public function viewTrainingRecords(User $user, Training $training): Response
    {
        return $user->account->trainings->contains($training)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user employee can view any employee training records.
     */
    public function viewAnyTrainingRecords(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::VIEW_ALL_TRAININGS)
            ? Response::allow()
            : Response::deny();
    }
}
