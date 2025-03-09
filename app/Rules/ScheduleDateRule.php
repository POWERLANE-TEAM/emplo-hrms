<?php

namespace App\Rules;

class ScheduleDateRule
{
    /**
     * Generates a validation rule string for a date that must be between the specified minimum and maximum dates.
     *
     * @param  string|null  $minDate  The minimum date in 'Y-m-d' format. Defaults to the current date if null.
     * @param  string|null  $maxDate  The maximum date in 'Y-m-d' format. Defaults to one month from the current date if null.
     * @return string The validation rule string.
     */
    public static function get(?string $minDate = null, ?string $maxDate = null): string
    {

        $timezone = config('app.server_timezone');
        $dateTimeZone = new \DateTimeZone($timezone);

        $minDate = $minDate ?? (new \DateTime('now', $dateTimeZone))->format('Y-m-d');
        $maxDate = $maxDate ?? (new \DateTime('now', $dateTimeZone))->modify('+1 month')->format('Y-m-d');

        if (app()->environment() == 'local') {
            return '';
        }

        return 'date|after_or_equal:'.$minDate.'|before_or_equal:'.$maxDate;
    }
}
