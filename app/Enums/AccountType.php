<?php

namespace App\Enums;

enum AccountType: string
{
    case GUEST = 'guest';
    case APPLICANT = 'applicant';
    case EMPLOYEE = 'employee';


    // displays user-friendly account types in blade templates
    public function label(): string
    {
        return match ($this) {
            self::GUEST => 'Guest',
            self::APPLICANT => 'Applicant',
            self::EMPLOYEE => 'Employee',
        };
    }
}
