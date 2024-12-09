<?php

namespace App\Enums;

enum JobQualificationPriorityLevel: string
{
    case HP = 'High';
    case MP = 'Medium';
    case LP = 'Low';

    public function label(): string
    {
        return match ($this) {
            self::HP => 'High Priority',
            self::MP => 'Medium Priority',
            self::LP => 'Low Priority',
        };
    }

    public function getWeight(): int
    {
        return match ($this) {
            self::HP => 50,
            self::MP => 30,
            self::LP => 20,
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
