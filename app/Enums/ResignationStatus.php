<?php

namespace App\Enums;

enum ResignationStatus: int
{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;

    // displays user-friendly account statuses in blade templates
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
        };
    }
}
