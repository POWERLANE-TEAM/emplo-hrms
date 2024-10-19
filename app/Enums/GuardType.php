<?php

namespace App\Enums;

/**
 * References the guards declared at config/auth.php
 *
 * @see config/auth.php
 *
 *  file://./../../config/auth.php
 */
enum GuardType: string
{

    case DEFAULT = 'web';
    case EMPLOYEE = AccountType::EMPLOYEE->value;
    case ADMIN = 'admin';


    /**
     * Displays user-friendly guard types in blade templates.
     *
     * @return string The corresponding guard type.
     */
    public function label()
    {
        return match ($this) {
            self::DEFAULT => 'Web',
            self::EMPLOYEE => AccountType::EMPLOYEE->label(),
            self::ADMIN => 'Admin'
        };
    }

    /**
     * Get an array of all guard types.
     *
     * @return array An array of guard types.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
