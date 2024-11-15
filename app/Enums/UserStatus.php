<?php

namespace App\Enums;

enum UserStatus: int
{
    case ACTIVE = 1;
    case SUSPENDED = 2;
    case NOT_VERIFIED = 3;

    /**
     * Return user-friendly user status labels.
     * 
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::SUSPENDED => 'Suspended',
            self::NOT_VERIFIED => 'Unverified'
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::ACTIVE => 'Account is active',
            self::SUSPENDED => 'Account is suspended',
            self::NOT_VERIFIED => 'Account is not verified.'
        };
    }
}
