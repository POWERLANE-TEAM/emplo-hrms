<?php

namespace App\Enums;

enum RoutePrefix: string
{

    case DEFAULT = '';
    case EMPLOYEE = AccountType::EMPLOYEE->value;
    case ADVANCED = 'admin';


    /**
     * Displays user-friendly route prefixes in blade templates.
     *
     * @return string The corresponding route prefix.
     */
    public function label()
    {
        return match ($this) {
            self::DEFAULT => 'No prefix',
            self::EMPLOYEE => AccountType::EMPLOYEE->label(),
            self::ADVANCED => 'Admin'
        };
    }

    /**
     * Get an array of all route prefixes.
     *
     * @return array An array of route prefixes.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get an array of all route prefixes except the default.
     *
     * @return array An array of route prefixes.
     * NOT containing no empty prefix.
     */
    public static function valuesNotDefault(): array
    {
        return array_column(
            array_filter(self::cases(), fn($case) => $case !== self::DEFAULT),
            'value'
        );
    }
}
