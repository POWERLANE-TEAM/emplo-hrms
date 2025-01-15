<?php

namespace App\Enums;

enum JobFamily: string
{
    case OPERATIONS = 'Operations';
    case ADMINISTRATIVE = 'Administrative';
    case GA_SUPPORT = 'General Affairs-Support';
    case HR_OPERATIONS = 'Human Resources Operations';
    case ACCOUNTING = 'Accounting';
    
    public function getColor(): string
    {
        return match ($this) {
            self::OPERATIONS => 'blue',
            self::ADMINISTRATIVE => 'teal',
            self::GA_SUPPORT => 'green',
            self::HR_OPERATIONS => 'purple',
            self::ACCOUNTING => 'orange',
            default => 'purple',
        };
    }
}
