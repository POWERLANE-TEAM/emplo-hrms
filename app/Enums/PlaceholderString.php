<?php

namespace App\Enums;

enum PlaceholderString: string
{
    case NOT_PROVIDED = 'not provided';
    case UNAVAILABLE = 'unavailable';

    public function label()
    {
        return match ($this) {
            self::NOT_PROVIDED => 'Not provided',
            self::UNAVAILABLE => 'Unavailable,'
        };
    }
}
