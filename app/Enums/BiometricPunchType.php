<?php

namespace App\Enums;

enum BiometricPunchType: int
{
    case CHECK_IN = 0;
    case CHECK_OUT = 1;
    case OVERTIME_IN = 4;
    case OVERTIME_OUT = 5;

    public function getDescription()
    {
        return match ($this) {
            self::CHECK_IN => 'Check-In',
            self::CHECK_OUT => 'Check-Out',
            self::OVERTIME_IN => 'Overtime-In',
            self::OVERTIME_OUT => 'Overtime-Out',
        };
    }
}
