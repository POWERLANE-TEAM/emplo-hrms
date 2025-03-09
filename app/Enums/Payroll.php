<?php

namespace App\Enums;

use Illuminate\Support\Carbon;

enum Payroll: int
{
    case CUT_OFF_1 = 1;
    case CUT_OFF_2 = 2;

    public function getCutOffDescription(): string
    {
        return match ($this) {
            self::CUT_OFF_1 => __('11th - 25th of the month'),
            self::CUT_OFF_2 => __('26th - 10th of the month'),
        };
    }

    public function getPayoutDescription(): string
    {
        return match ($this) {
            self::CUT_OFF_1 => __('30th - 31st of the month'),
            self::CUT_OFF_2 => __('15th of the month'),
        };
    }

    /**
     * Get the start and end of the cut-off period for each case.
     */
    private function getPeriodForCase(Carbon $date): array
    {
        return match ($this) {
            self::CUT_OFF_1 => $this->getFirstCutOffPeriod($date),
            self::CUT_OFF_2 => $this->getSecondCutOffPeriod($date),
        };
    }

    /**
     * Get the first cut-off period (11th - 25th).
     */
    private function getFirstCutOffPeriod(Carbon $date): array
    {
        $start = $date->copy()->startOfMonth()->addDays(10);
        $end = $date->copy()->startOfMonth()->addDays(24);

        return compact('start', 'end');
    }

    /**
     * Get the second cut-off period (26th - 10th of next month).
     */
    private function getSecondCutOffPeriod(Carbon $date): array
    {
        if ($date->day <= 10) {
            $previousMonth = $date->copy()->subMonth();
            $start = $previousMonth->copy()->startOfMonth()->addDays(25);
            $end = $date->copy()->startOfMonth()->addDays(9);
        } else {
            $start = $date->copy()->startOfMonth()->addDays(25);
            $end = $date->copy()->addMonth()->startOfMonth()->addDays(9);
        }

        return compact('start', 'end');
    }

    /**
     * Get cut-off period based on the given date.
     */
    public static function getCutOffPeriod(?string $date = null, bool $isReadableFormat = false): array
    {
        $date = Carbon::parse($date) ?? now();

        $cutOffPeriod = match (true) {
            $date->day <= 10 => self::CUT_OFF_2,
            $date->day <= 25 => self::CUT_OFF_1,
            default => self::CUT_OFF_2,
        };

        $period = $cutOffPeriod->getPeriodForCase($date);

        return $isReadableFormat
            ? ['start' => $period['start']->format('F d'), 'end' => $period['end']->format('F d, Y')]
            : $period;
    }

    /**
     * Get payout date based on the given date.
     */
    public static function getPayoutDate(?string $date = null, bool $isReadableFormat = false)
    {
        $date = Carbon::parse($date) ?? now();

        $payoutDate = match (true) {
            $date->day <= 10 => $date->copy()->startOfMonth()->addDays(14),
            $date->day <= 25 => $date->copy()->endOfMonth(),
            default => $date->copy()->addMonth()->startOfMonth()->addDays(14),
        };

        return $isReadableFormat
            ? $payoutDate->format('F d, Y')
            : $payoutDate;
    }

    /**
     * Determine the cut-off period based on the given date.
     */
    public static function getCutOffPeriodForDate(Carbon $date): Payroll
    {
        if ($date->day >= 11 && $date->day <= 25) {
            return Payroll::CUT_OFF_1;
        }

        if ($date->day >= 26 || $date->day <= 10) {
            return Payroll::CUT_OFF_2;
        }

        return Payroll::CUT_OFF_2;
    }
}
