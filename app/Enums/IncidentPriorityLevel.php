<?php

namespace App\Enums;

enum IncidentPriorityLevel: int
{
    case LOW = 1;
    case MEDIUM = 2;
    case HIGH = 3;

    public function getLabel()
    {
        return match ($this) {
            self::LOW => 'Low Priority',
            self::MEDIUM => 'Medium Priority',
            self::HIGH => 'High Priority',
        };
    }

    public function getColor()
    {
        //
    }

    public function getDescription()
    {
        // description for each priority levels
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->getLabel();

            return $carry;
        }, []);
    }
}
