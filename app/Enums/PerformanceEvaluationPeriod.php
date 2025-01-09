<?php

namespace App\Enums;

enum PerformanceEvaluationPeriod: string
{
    case THIRD_MONTH = 'third month';
    case FIFTH_MONTH = 'fifth month';
    case FINAL_MONTH = 'final month';
    case ANNUAL = 'annual';

    public function getLabel(): string
    {
        return match ($this) {
            self::THIRD_MONTH => 'Third Month',
            self::FIFTH_MONTH => 'Fifth Month',
            self::FINAL_MONTH => 'Final Month',
            self::ANNUAL => 'Annual',
        };
    }

    public function getShorterLabel(): string
    {
        return match($this) {
            self::THIRD_MONTH => '3',
            self::FIFTH_MONTH => '5',
            self::FINAL_MONTH => 'Final',
            self::ANNUAL => 'Annual',
        };
    }
}
