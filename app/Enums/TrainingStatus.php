<?php

namespace App\Enums;

enum TrainingStatus: string
{
    case COMPLETED = 'completed';
    case IN_PROGRESS = 'in progress';
    case CANCELLED = 'cancelled';
    case NOT_STARTED = 'not started';

    public function getLabel(): string
    {
        return match ($this) {
            self::COMPLETED => 'Completed',
            self::IN_PROGRESS => 'In-Progress',
            self::CANCELLED => 'Cancelled',
            self::NOT_STARTED => 'Not Started',
        };
    }

    public static function options(): array
    {
        return array_reduce(self::cases(), function ($carry, $case) {
            $carry[$case->value] = $case->getLabel();

            return $carry;
        }, []);
    }
}
