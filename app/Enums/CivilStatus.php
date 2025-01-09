<?php

namespace App\Enums;

enum CivilStatus: string
{
    case SINGLE = 'single';
    case MARRIED = 'married';
    case WIDOWED = 'widowed';
    case LEGALLY_SEPARATED = 'legally separated';

    public function label()
    {
        return match ($this) {
            self::SINGLE => 'Single',
            self::MARRIED => 'Married',
            self::WIDOWED => 'Widowed',
            self::LEGALLY_SEPARATED => 'Legally Separated',
        };
    }

    /**
     * Return array of each cases and scalar values.
     */
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->label();

            return $carry;
        }, []);
    }
}
