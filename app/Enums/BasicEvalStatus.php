<?php

namespace App\Enums;

enum BasicEvalStatus: int
{
    case PASSED = 1;
    case FAILED = 0;

    // displays user-friendly account statuses in blade templates
    public function label(): string
    {
        return match ($this) {
            self::PASSED => 'Passed',
            self::FAILED => 'Failed',
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

    public static function labelForValue(?int $value): string
    {
        if (is_null($value)) {
            return 'No Result';
        }

        try {
            return self::from($value)->label();
        } catch (\ValueError) {
            return 'No Result';
        }
    }
}
