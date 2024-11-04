<?php

namespace App\Enums;

enum Sex: string
{
    case MALE = 'male';
    case FEMALE = 'female';

    public function label()
    {
        return match ($this) {
            self::MALE => 'Male',
            self::FEMALE => 'Female',
        };
    }

    /**
     * Return array of each cases and scalar values.
     * 
     * @return array
     */
    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->label();
            return $carry;
        }, []);
    }
}
