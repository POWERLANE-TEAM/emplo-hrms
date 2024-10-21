<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    /**
     * Return user-friendly application status labels.
     */
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending approval',
            self::APPROVED => 'Approved application',
            self::REJECTED => 'Rejected application',
        };
    }
}
