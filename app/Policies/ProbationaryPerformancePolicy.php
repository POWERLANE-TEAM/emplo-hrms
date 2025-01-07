<?php

namespace App\Policies;

use App\Enums\UserPermission;
use App\Models\User;
use App\Models\Employee;
use App\Enums\EmploymentStatus;
use Illuminate\Auth\Access\Response;

class ProbationaryPerformancePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function evaluateProbationaryPerformance(User $user, Employee $employee)
    {
        if (! $this->isEmployeeProbationary($employee)) {
            return Response::deny('This employee is not of probationary status.');
        }

        return Response::allow();
    }

    public function isEmployeeProbationary(Employee $employee): bool
    {
        return $employee->status->emp_status_name === EmploymentStatus::PROBATIONARY->label();
    }

    public function openThirdMonthEvaluationPeriod()
    {
        //
    }

    public function openFifthMonthEvaluationPeriod()
    {
        //
    }

    public function openFinalMonthEvaluationPeriod()
    {
        //
    }

    public function signProbationarySubordinateEvaluationForm(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_SECOND)
            ? Response::allow()
            : Response::deny();
    }

    public function signAnyProbationaryEvaluationForm(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_THIRD)
            ? Response::allow()
            : Response::deny();
    }

    public function signProbationaryEvaluationFormFinal(User $user)
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_FOURTH)
            ? Response::allow()
            : Response::deny();
    }
}
