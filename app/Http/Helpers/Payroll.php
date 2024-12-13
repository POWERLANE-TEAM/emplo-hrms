<?php

namespace App\Http\Helpers;

use Illuminate\Support\Carbon;

class Payroll
{
    /**
     * Getter for cut off periods.
     *
     * @param string|null $date
     * @param bool $isReadableFormat format the values similar to December 01 - December 15, 2024
     * @return array
     */
    public static function getCutOffPeriod(?string $date = null, bool $isReadableFormat = false): array
    {
        $date = Carbon::parse($date) ?? now();
        
        if ($date->day <= 10) {
            $start = $date->copy()->subMonth()->endOfMonth()->subDays(4);
            $end = $date->copy()->startOfMonth()->addDays(9);
        } elseif ($date->day <= 25) {
            $start = $date->copy()->startOfMonth()->addDays(10);
            $end = $date->copy()->startOfMonth()->addDays(24);
        } else {
            $start = $date->copy()->startOfMonth()->addDays(25);
            $end = $date->copy()->addMonth()->startOfMonth()->addDays(9);
        }
    
        return $isReadableFormat
            ? ['start' => $start->format('F d'), 'end' => $end->format('F d, Y')]
            : compact('start', 'end');
    }    
    
    /**
     * Getter for payout date.
     */
    public static function getPayoutDate(?string $date = null, bool $isReadableFormat = false)
    {
        $date = Carbon::parse($date) ?? now();
        $payoutDate = null;
        
        if ($date->day <= 10) {
            $payoutDate = $date->copy()->startOfMonth()->addDays(14);
        } elseif ($date->day <= 25) {
            $payoutDate = $date->copy()->endOfMonth();
        } else {
            $payoutDate = $date->copy()->addMonth()->startOfMonth()->addDays(14);
        }
    
        return $isReadableFormat
            ? $payoutDate->format('F d, Y') 
            : $payoutDate;
    }
}