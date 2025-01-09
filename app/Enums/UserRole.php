<?php

namespace App\Enums;

enum UserRole: string
{
    case BASIC = 'basic';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';

    /**
     * Return user-friendly role labels.
     */
    public function label(): string
    {
        return match ($this) {
            self::BASIC => 'Basic Level',
            self::INTERMEDIATE => 'Intermediate Level',
            self::ADVANCED => 'Advanced Level',
        };
    }

    /**
     * @return array An array of all user roles values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
