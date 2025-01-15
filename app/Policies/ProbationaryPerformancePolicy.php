<?php

namespace App\Policies;

use App\Enums\EmploymentStatus;
use App\Enums\UserPermission;
use App\Models\Employee;
use App\Models\ProbationaryPerformance;
use App\Models\User;
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

    /**
     * Idk man
     *
     * @return Response
     */
    public function evaluateProbationaryPerformance(User $user, Employee $employee)
    {
        if (! $this->isEmployeeProbationary($employee)) {
            return Response::deny('This employee is not of probationary status.');
        }

        return Response::allow();
    }

    /**
     * Check if employee is of probationary status.
     */
    public function isEmployeeProbationary(Employee $employee): bool
    {
        return $employee->status->emp_status_name === EmploymentStatus::PROBATIONARY->label();
    }

    /**
     * Check if user can approve probationary employee under his jursidction's performance evaluation form.
     */
    public function signProbationarySubordinateEvaluationForm(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_SECOND)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user can approve / sign any probationary employees perfomrance evaluation form.
     */
    public function signAnyProbationaryEvaluationForm(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_THIRD)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user is the last to approve / sign any probationary employees perfomrance evaluation form.
     *
     * @return Response
     */
    public function signProbationaryEvaluationFormFinal(User $user)
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_FOURTH)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if probationary employee has acknowledged the performance evaluation results.
     *
     * @param  mixed  $user
     */
    public function hasProbationaryEvaluateeAcknowledged(?User $user, ProbationaryPerformance $performance): bool
    {
        return $performance->is_employee_acknowledged;
    }
}
