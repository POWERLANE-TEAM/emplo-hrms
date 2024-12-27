<?php

namespace App\Enums;

enum StatusBadge: string
{
    case APPROVED = 'approved';
    case DENIED = 'denied';
    case PENDING = 'pending';

    public function getColor()
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::DENIED => 'danger',
            self::PENDING => 'info'
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::APPROVED => __('APPROVED'),
            self::DENIED => __('DENIED'),
            self::PENDING => __('PENDING'),
        };
    }
}
