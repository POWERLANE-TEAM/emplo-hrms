<?php

namespace App\Enums;

enum PerformanceEvaluationPeriod: string
{
    case THIRD_MONTH = 'third month';
    case FIFTH_MONTH = 'fifth month';
    case FINAL_MONTH = 'final month';
    case ANNUAL = 'annual';
}
