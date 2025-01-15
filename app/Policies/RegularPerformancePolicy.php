<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Employee;
use App\Enums\UserPermission;
use Illuminate\Support\Carbon;
use App\Enums\EmploymentStatus;
use App\Models\RegularPerformance;
use Illuminate\Auth\Access\Response;
use App\Models\RegularPerformancePeriod;

class RegularPerformancePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Check if evaluation period being set is not of same year with the finished period.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function openRegularsEvaluationPeriod(User $user): Response
    {
        $latestFinishedPeriod = RegularPerformancePeriod::latest('start_date')->first();

        $latestFinishedPeriodDate = Carbon::parse($latestFinishedPeriod->end_date);
        
        return $latestFinishedPeriodDate->year === now()->year
            ? Response::deny(__('Sorry, annual performance evaluation for this period is finished.'))
            : Response::allow();
    }

    /**
     * Checks for the ff:
     * 
     * - if user can receive / sign a subordionate performance evaluation form.
     * - if user can receive / sign any regular employee evaluation form.
     * - if user can / is the last one to receive /sign regular employee evaluation form.
     * - if employee / evaluatee account is active.
     * - if evaluation period is still open.
     * - if employee is under the same job family as the user.
     * - if employee is of "Regular" employment status.
     * - if user is trying to access his own evaluation form.
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Employee $employee
     * @return \Illuminate\Auth\Access\Response
     */
    public function evaluateRegularsPerformance(User $user, Employee $employee): Response
    {        
        if (! $this->isEmployeeAccountActive($employee)) {
            return Response::deny(__('This employee\'s account is inactive.'));
        }

        if ($this->isMe($user, $employee)) {
            return Response::deny(__('You\'re not allowed to evaluate yourself.'));
        }

        if (! $this->isEvaluationPeriodOpen()) {
            return Response::deny();
        }

        if (! $this->isEmployeeUnderJurisdiction($user, $employee)) {
            return Response::deny(__("This employee is not under the job family."));
        }

        if (! $user->hasPermissionTo(UserPermission::ASSIGN_PERFORMANCE_EVAL_SCORE)) {
            return Response::deny(__('Sorry, you don\'t have sufficient permission to be a performance evaluator.'));
        }

        if (! $this->isEmployeeRegular($employee)) {
            return Response::deny(__('This employee is not regular.'));
        }

        return Response::allow();
    }

    /**
     * Check if user can receive / sign a subordionate performance evaluation form.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Auth\Access\Response
     */
    public function signRegularSubordinateEvaluationForm(User $user): Response
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_SECOND)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Check if user can receive / sign any regular employee evaluation form.
     * 
     * @param \App\Models\User $user
     * @return Response
     */
    public function signAnyRegularEvaluationForm(User $user)
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_THIRD)
            ? Response::allow()
            : Response::deny();  
    }

    /**
     * Check if user can / is the last one to receive /sign regular employee evaluation form.
     * 
     * @param \App\Models\User $user
     * @return Response
     */
    public function signRegularEvaluationFormFinal(User $user)
    {
        return $user->hasPermissionTo(UserPermission::APPROVE_PERFORMANCE_EVALUATION_FOURTH)
            ? Response::allow()
            : Response::deny();  
    }

    /**
     * Check if employee / evaluatee account is active.
     * 
     * @param \App\Models\Employee $employee
     * @return bool
     */
    public function isEmployeeAccountActive(Employee $employee): bool
    {
        $accountCheck = new UserStatusPolicy;

        return $accountCheck->isAccountActive($employee->account);
    }

    /**
     * Check if evaluation period is still open.
     * 
     * @return bool
     */
    public function isEvaluationPeriodOpen()
    {
        $evaluationPeriod = RegularPerformancePeriod::latest()->first();

        $startDate = Carbon::parse($evaluationPeriod->start_date);
        $endDate = Carbon::parse($evaluationPeriod->end_date);

        return now()->greaterThan($startDate) && now()->lessThan($endDate);
    }

    /**
     * Check if employee is under the same job family as the user.
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Employee $employee
     * @return bool
     */
    public function isEmployeeUnderJurisdiction(User $user, Employee $employee): bool
    {
        $userJobFamily = $user->account->jobTitle->jobFamily;
        $employeeJobFamily = $employee->jobTitle->jobFamily;

        return $userJobFamily->is($employeeJobFamily);
    }

    /**
     * Check if employee is of "Regular" employment status.
     * 
     * @param \App\Models\Employee $employee
     * @return bool
     */
    public function isEmployeeRegular(Employee $employee): bool
    {
        return $employee->status->emp_status_name === EmploymentStatus::REGULAR->label();
    }

    /**
     * Check if user is trying to access his own evaluation form.
     * 
     * @param \App\Models\User $user
     * @param \App\Models\Employee $employee
     * @return bool
     */
    public function isMe(User $user, Employee $employee): bool
    {
        return $employee->account->is($user);
    }

    /**
     * Check if evaluatee has acknowledged the result of performance evaluation. 
     * 
     * @param mixed $user
     * @param \App\Models\RegularPerformance $performance
     * @return bool
     */
    public function hasRegularEvaluateeAcknowledged(?User $user, RegularPerformance $performance): bool
    {
        return $performance->is_employee_acknowledged;
    }
}
