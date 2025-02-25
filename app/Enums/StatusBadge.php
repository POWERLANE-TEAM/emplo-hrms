<?php

namespace App\Enums;

enum StatusBadge: string
{
    case APPROVED = 'approved';
    case DENIED = 'denied';
    case PENDING = 'pending';
    case INVALID = 'invalid';
    case INCOMPLETE = 'incomplete';

    public function getColor()
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::DENIED,
            self::INVALID => 'danger',
            self::PENDING => 'info',
            self::INCOMPLETE => 'warning',
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::APPROVED => __('APPROVED'),
            self::DENIED => __('DENIED'),
            self::INVALID => __('INVALID'),
            self::PENDING => __('PENDING'),
            self::INCOMPLETE => __('INCOMPLETE'),
        };
    }
}
