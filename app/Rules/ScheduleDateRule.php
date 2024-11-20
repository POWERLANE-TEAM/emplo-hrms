<?php

namespace App\Rules;


class ScheduleDateRule
{

    /**
     * Generates a validation rule string for a date that must be between the specified minimum and maximum dates.
     *
     * @param string|null $minDate The minimum date in 'Y-m-d' format. Defaults to the current date if null.
     * @param string|null $maxDate The maximum date in 'Y-m-d' format. Defaults to one month from the current date if null.
     * @return string The validation rule string.
     */
    public static function get(?string $minDate = null, ?string $maxDate = null): string
    {
        $minDate = $minDate ?? date('Y-m-d');
        $maxDate = $maxDate ?? date('Y-m-d', strtotime('+1 month'));

        return 'date|after_or_equal:' . $minDate . '|before_or_equal:' . $maxDate;
    }
}
