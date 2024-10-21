<?php

namespace App\Enums;

enum UserRole: string
{
    case BASIC = 'basic';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';

    /**
     * Return user-friendly role labels.
     * 
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::BASIC => 'Basic Level',
            self::INTERMEDIATE => 'Intermediate Level',
            self::ADVANCED => 'Advanced Level',
        };
    }
}
