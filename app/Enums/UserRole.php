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

    /**
     * Get an array of all roles types.
     *
     * @return array An array of roles types.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
