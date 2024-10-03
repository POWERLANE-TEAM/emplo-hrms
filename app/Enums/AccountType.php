<?php

namespace App\Enums;

enum AccountType: string
{
    case APPLICANT = 'applicant';
    case EMPLOYEE = 'employee';


    // displays user-friendly account statuses in blade templates
    public function label(): string
    {
        return match ($this) {
            self::APPLICANT => 'Applicant',
            self::EMPLOYEE => 'Employee',
        };
    }
}
