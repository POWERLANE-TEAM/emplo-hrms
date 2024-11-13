<?php

namespace App\Enums;

enum JobQualificationPriorityLevel: string
{
    case HP = 'hp';
    case MP = 'mp';
    case LP = 'lp';

    public function label(): string
    {
        return match($this) {
            self::HP => 'High Priority',
            self::MP => 'Medium Priority',
            self::LP => 'Low Priority',
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
