<?php

namespace App\Enums;

use Google\Service\ShoppingContent\Resource\Returnaddress;

enum IssueStatus: int
{
    case OPEN = 1;
    case RESOLVED = 2;
    case CLOSED = 3;

    public function getLabel()
    {
        return match ($this) {
            self::OPEN => __('Open'),
            self::RESOLVED => __('Resolved'),
            self::CLOSED => __('Closed'),
        };
    }

    public function getColor()
    {
        return match ($this) {
            self::RESOLVED => 'success',
            self::OPEN => 'info',
            self::CLOSED => 'danger'
        };
    }

    public function getIcon()
    {
        return match ($this) {
            self::RESOLVED => 'circle-check-big',
            self::OPEN => 'square-pen',
            self::CLOSED => 'circle-slash'
        };
    }
}
