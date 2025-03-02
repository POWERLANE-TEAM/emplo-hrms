<?php

namespace App\Enums;

enum InterviewRating: string
{
    case UNSATISFACTORY = 'd';
    case FAIR = 'c';
    case GOOD = 'b';
    case OUTSTANDING = 'a';

    // displays user-friendly account statuses in blade templates
    public function label(): string
    {
        return match ($this) {
            self::OUTSTANDING => 'Outstanding',
            self::GOOD => 'Good',
            self::FAIR => 'Fair',
            self::UNSATISFACTORY => 'Unsatisfactory',
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

    public static function values(): array
    {
        return collect(self::cases())
            ->pluck('value')
            ->all();
    }
}
