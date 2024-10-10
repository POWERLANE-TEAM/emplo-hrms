<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    // displays user-friendly account statuses in blade templates
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending approval',
            self::APPROVED => 'Approved application',
            self::REJECTED => 'Rejected application',
        };
    }
}
