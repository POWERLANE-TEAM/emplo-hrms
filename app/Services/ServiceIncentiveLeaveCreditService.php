<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\ServiceIncentiveLeave;
use App\Models\ServiceIncentiveLeaveCredit;
use Illuminate\Database\Eloquent\Collection;

class ServiceIncentiveLeaveCreditService
{
    public function __construct()
    {
        //
    }

    public function getActiveEmployees(): Collection
    {
        return Employee::query()
            ->activeEmploymentStatus()
            ->with([
                'silCredit',
                'jobDetail' => fn ($q) => $q->select([
                    'emp_job_detail_id', 
                    'employee_id', 
                    'hired_at'
                ])
            ])
            ->get();
    }

    public function filterExperiencedEmployees(Collection $employees): Collection
    {
        return $employees->filter(function ($employee) {
            return $employee->jobDetail->hired_at->lte(today()->subYear());
        });
    }

    public function filterHiredThisMonthAndDay(Collection $employees): Collection
    {
        return $employees->filter(function ($employee) {
            $monthDayHired = $employee->jobDetail->hired_at->format('m-d');

            return $monthDayHired == today()->format('m-d');
        });
    }

    public function getIncrease(int $yrsInService): int
    {
        $increaseMap = ServiceIncentiveLeave::silCreditsIncreaseMap();

        return $increaseMap[$yrsInService] ?? 0;
    }

    public function resetSilCredits(int $yrsInService): int
    {
        $resetMap = ServiceIncentiveLeave::silCreditsYearlyResetMap();

        return $resetMap[$yrsInService]->value ?? 0;
    }

    public function getServiceDuration(Carbon $dateHired, ?Carbon $interval = null): int|float
    {
        $interval ??= today();

        return round($dateHired->diffInYears($interval));
    }

    public function increaseSilCredits(Employee $employee, int $increase): array
    {
        $slCredits = $employee->silCredit->sick_leave_credits + $increase;
        $vlCredits = $employee->silCredit->vacation_leave_credits + $increase;

        return compact('slCredits', 'vlCredits');
    }

    public function getTotalIncreaseSilCredits(Employee $employee, int $yrsInService): object
    {
        $increase = $this->getIncrease($yrsInService);

        return (object) $this->increaseSilCredits($employee, $increase);
    }

    public function save(array $credits): void
    {
        DB::transaction(function () use ($credits) {
            ServiceIncentiveLeaveCredit::upsert(
                $credits,
                uniqueBy: ['employee_id'],
                update: [
                    'vacation_leave_credits',
                    'sick_leave_credits',
                ],
            );
        }, 5);
    }
}