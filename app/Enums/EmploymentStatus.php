<?php

namespace App\Enums;

use Illuminate\Support\Carbon;

enum EmploymentStatus: int
{
    case PROBATIONARY = 1;
    case REGULAR = 2;
    case RESIGNED = 3;
    case RETIRED = 4;
    case TERMINATED = 5;

    /**
     * Return user-friendly employment status labels.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::PROBATIONARY => 'Probationary',
            self::REGULAR => 'Regular',
            self::RESIGNED => 'Resigned',
            self::RETIRED => 'Retired',
            self::TERMINATED => 'Terminated',
        };
    }

    /**
     * Find the employment status by case-insensitive string and return the value.
     *
     * @param string $label
     * @return int|null
     */
    public static function findByLabel(string $label): ?int
    {
        foreach (self::cases() as $case) {
            if (strcasecmp($case->label(), $label) === 0) {
                return $case->value;
            }
        }
        return null;
    }

    /**
     * Add 5 years to employee date of separation for data retention policy period.
     * 
     * @param \Illuminate\Support\Carbon|string $separationDate
     * @return \Carbon\Carbon|\Carbon\CarbonInterface
     */
    public static function separatedEmployeeDataRetentionPeriod(Carbon|string $separationDate)
    {   
        $separationDate = is_string($separationDate) 
            ? Carbon::parse($separationDate) 
            : $separationDate;

        return $separationDate->addYears(5);
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
