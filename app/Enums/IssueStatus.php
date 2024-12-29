<?php

namespace App\Enums;

enum IssueStatus: int
{
    case ONGOING = 1;
    case RESOLVED = 2;
    case CLOSED = 3;

    public function getLabel()
    {
        return match ($this) {
            self::ONGOING => __('Ongoing'),
            self::RESOLVED => __('Resolved'),
            self::CLOSED => __('Closed'),
        };
    }
}
