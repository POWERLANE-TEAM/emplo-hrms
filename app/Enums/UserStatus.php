<?php

namespace App\Enums;

enum UserStatus: int
{
    case ACTIVE = 1;
    case INACTIVE = 2;
    case SUSPENDED = 3;

    /**
     * Return user-friendly user status labels.
     */
    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::SUSPENDED => 'Suspended',
        };
    }
}
