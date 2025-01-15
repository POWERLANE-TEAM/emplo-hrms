<?php

namespace App\Enums;

use Illuminate\Support\Carbon;

enum ServiceIncentiveLeave: int
{
    // vacation and sick leave credits for initial
    case Q1 = 4;
    case Q2 = 3;
    case Q3 = 2;
    case Q4 = 1;

    public function getLabel(bool $inMonths = false): string
    {
        if ($inMonths) {
            return match ($this) {
                self::Q1 => __('January - March'),
                self::Q2 => __('April - June'),
                self::Q3 => __('July - September'),
                self::Q4 => __('October - December'),
            };            
        }

        return match ($this) {
            self::Q1 => __('First Quarter'),
            self::Q2 => __('Second Quarter'),
            self::Q3 => __('Third Quarter'),
            self::Q4 => __('Fourth Quarter'),
        };
    }

    public function getQuarter(int $quarter): array
    {
        return match ($quarter) {
            1 => $this->getFirstQuarter(),
            2 => $this->getSecondQuarter(),
            3 => $this->getThirdQuarter(),
            4 => $this->getFourthQuarter(),
        };
    }

    public function getFirstQuarter(): array
    {
        return [
            Carbon::JANUARY,
            Carbon::FEBRUARY,
            Carbon::MARCH,
        ];
    }

    public function getSecondQuarter(): array
    {
        return [
            Carbon::APRIL,
            Carbon::MAY,
            Carbon::JUNE,
        ]; 
    }

    public function getThirdQuarter(): array
    {
        return [
            Carbon::JULY,
            Carbon::AUGUST,
            Carbon::SEPTEMBER,
        ]; 
    }

    public function getFourthQuarter(): array
    {
        return [
            Carbon::OCTOBER,
            Carbon::NOVEMBER,
            Carbon::DECEMBER,
        ]; 
    }
}
