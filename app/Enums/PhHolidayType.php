<?php

namespace App\Enums;

enum PhHolidayType: string
{
    case REGULAR = 'regular';
    case SPECIAL_NON_WORKING = 'special non-working';
    case SPECIAL_WORKING = 'special working';

    public function label() 
    {
        return match ($this) {
            self::REGULAR => 'Regular Holiday',
            self::SPECIAL_NON_WORKING => 'Special (Non-working) Holiday',
            self::SPECIAL_WORKING => 'Special (Working) Holiday',
        };
    }

    public function color()
    {
        //
    }
}
