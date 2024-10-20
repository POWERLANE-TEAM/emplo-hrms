<?php

namespace App\Enums;

enum AccountType: string
{
    case GUEST = 'guest';
    case APPLICANT = 'applicant';
    case EMPLOYEE = 'employee';

    /**
     * Return user-friendly account type labels.
     * 
     * @return string $coolstuff
     */
    public function label(): string
    {
        return match ($this) {
            self::GUEST => 'Guest',
            self::APPLICANT => 'Applicant',
            self::EMPLOYEE => 'Employee',
        };
    }
}
