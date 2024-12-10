<?php

namespace App\Http\Helpers;

use Illuminate\Support\Carbon;

class Payroll
{
    /**
     * Getter for cut off periods
     */
    public static function getCutOffPeriod(): array
    {
        $today = now();
    
        if ($today->day <= 10) {
            $start = $today->copy()->subMonth()->endOfMonth()->subDays(4);
            $end = $today->copy()->startOfMonth()->addDays(9);
        } elseif ($today->day <= 25) {
            $start = $today->copy()->startOfMonth()->addDays(10);
            $end = $today->copy()->startOfMonth()->addDays(24);
        } else {
            $start = $today->copy()->startOfMonth()->addDays(25);
            $end = $today->copy()->addMonth()->startOfMonth()->addDays(9);
        }
    
        return compact('start', 'end');
    }
    
    /**
     * Getter for payout date.
     */
    public static function getPayoutDate(): Carbon
    {
        $today = now();

        if ($today->day <= 10) {
            return $today->copy()->startOfMonth()->addDays(14);
        } elseif ($today->day <= 25) {
            return $today->copy()->endOfMonth();
        } else {
            return $today->copy()->addMonth()->startOfMonth()->addDays(14);
        }
    }
}