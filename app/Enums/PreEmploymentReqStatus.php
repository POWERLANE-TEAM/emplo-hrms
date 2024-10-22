<?php

namespace App\Enums;

enum PreEmploymentReqStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case INVALID = 'invalid';

    // displays user-friendly account statuses in blade templates
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::INVALID => 'Invalid',
        };
    }
}
