<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait OvertimeUtils
{
    /**
     * Filter collection of `Overtime` models if authorized and is of the same payroll period.
     *
     * @return Collection<mixed, mixed>
     */
    public function validateOvertimeRecords(Collection $overtimes)
    {
        return $overtimes->filter(function ($ot) {
            return
                $ot->authorizer_signed_at &&
                $ot->payrollApproval->payroll_id === $this->latestPayroll->payroll_id;
        });
    }

    /**
     * Add the difference in hours of each overtime's start time against end time.
     */
    public function sumOvertimes(Collection $validatedOvertimes): float|int
    {
        return $validatedOvertimes->sum(fn ($ot) => $ot->start_time->diffInHours($ot->end_time));
    }
}
