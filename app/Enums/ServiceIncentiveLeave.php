<?php

namespace App\Enums;

use Exception;
use Carbon\CarbonInterface;

enum ServiceIncentiveLeave: int
{
    case Q1 = 4;

    case Q2 = 3;

    case Q3 = 2;

    case Q4 = 1;

    case ONE_YR_SINCE_HIRE = 5;

    case TWO_YRS_SINCE_HIRE = 7;

    case THREE_YRS_SINCE_HIRE = 10;

    case FOUR_YRS_SINCE_HIRE = 12;

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

    public static function getFirstQuarter(): array
    {
        return [
            CarbonInterface::JANUARY,
            CarbonInterface::FEBRUARY,
            CarbonInterface::MARCH,
        ];
    }

    public static function getSecondQuarter(): array
    {
        return [
            CarbonInterface::APRIL,
            CarbonInterface::MAY,
            CarbonInterface::JUNE,
        ]; 
    }

    public static function getThirdQuarter(): array
    {
        return [
            CarbonInterface::JULY,
            CarbonInterface::AUGUST,
            CarbonInterface::SEPTEMBER,
        ]; 
    }

    public static function getFourthQuarter(): array
    {
        return [
            CarbonInterface::OCTOBER,
            CarbonInterface::NOVEMBER,
            CarbonInterface::DECEMBER,
        ]; 
    }

    public static function getAllQuarters(): array
    {
        return [
            'first_quarter' => [
                'credits' => self::Q1->value,
                'months' => self::getFirstQuarter()
            ],
            'second_quarter' => [
                'credits' => self::Q2->value,
                'months' => self::getSecondQuarter()
            ],
            'third_quarter' => [
                'credits' => self::Q3->value,
                'months' => self::getThirdQuarter()
            ],
            'fourth_quarter' => [
                'credits' => self::Q4->value,
                'months' => self::getFirstQuarter()
            ],
        ];
    }

    public static function silCreditsIncreaseMap(): array
    {
        return [
            4 => 2,
            3 => 3,
            2 => 2,
            1 => 1,
        ];
    }

    public static function silCreditsYearlyResetMap(): array
    {
        return [
            4 => self::FOUR_YRS_SINCE_HIRE,
            3 => self::THREE_YRS_SINCE_HIRE,
            2 => self::TWO_YRS_SINCE_HIRE,
            1 => self::ONE_YR_SINCE_HIRE,
            0 => self::Q1,
        ];
    }

    public static function silCreditsMap(bool $yrsInService = false, bool $yearlyReset = false): array
    {
        if (! $yrsInService && ! $yearlyReset) {
            throw new Exception(__("No flag provided to the method."));
        }
        
        if ($yrsInService) {
            return self::silCreditsIncreaseMap();
        }

        if ($yearlyReset) {
            return self::silCreditsYearlyResetMap();
        }

        return [
            self::silCreditsIncreaseMap(),
            self::silCreditsYearlyResetMap(),
        ];
    }
}
