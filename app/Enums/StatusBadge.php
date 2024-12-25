<?php

namespace App\Enums;

enum StatusBadge: string
{
    case APPROVED = 'approved';
    case DENIED = 'denied';
    case PENDING = 'pending';
    case FOR_APPROVAL = 'for approval';

    public function getColor()
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::DENIED => 'danger',
            self::PENDING, self::FOR_APPROVAL => 'info'
        };
    }

    public function getLabel()
    {
        return match ($this) {
            self::APPROVED => __('APPROVED'),
            self::DENIED => __('DENIED'),
            self::PENDING => __('PENDING'),
            self::FOR_APPROVAL => __('FOR APPROVAL'),
        };
    }
}
