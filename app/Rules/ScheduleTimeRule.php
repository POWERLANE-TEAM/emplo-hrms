<?php

namespace App\Rules;

use App\Enums\UserRole;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ScheduleTimeRule implements ValidationRule
{
    protected $date;

    public $maxTime;

    public $minTime;

    public function __construct(string $date, ?string $maxTime = '22:00', ?string $minTime = '05:30')
    {
        $this->date = $date;
        $this->maxTime = $maxTime;
        $this->minTime = $minTime;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $currentTime = date('H:i');

        if ($this->date == date('Y-m-d')) {
            $this->validateToday($value, $currentTime, $fail);
        } else {
            $this->validateOtherDays($value, $fail);
        }
    }

    protected function validateToday($value, $currentTime, Closure $fail): void
    {

        $valueTime = date('H:i', strtotime($value));
        if ($valueTime < $currentTime && ! auth()->user()->hasRole(UserRole::ADVANCED)) {
            $fail($this->message('after_or_equal', $currentTime));
        }

        if ($valueTime > $this->maxTime) {
            $fail($this->message('before_or_equal', $this->maxTime));
        }
    }

    protected function validateOtherDays($value, Closure $fail): void
    {
        $valueTime = date('H:i', strtotime($value));
        if ($valueTime < $this->minTime) {
            $fail($this->message('after_or_equal', $this->minTime));
        }

        if ($valueTime > $this->maxTime) {
            $fail($this->message('before_or_equal', $this->maxTime));
        }
    }

    public function message($validationString, $date)
    {
        $time12HourFormat = date('g:i A', strtotime($date));

        return __('validation.'.$validationString, ['date' => $time12HourFormat]);
    }

    /**
     * Generates a validation rule string for a given date and time range.
     *
     * @param  string  $date  The date for which the validation rule is generated. Expected format is 'Y-m-d'.
     * @param  string  $maxTime  The maximum time for the validation rule. Expected format is 'H:i'.
     * @param  string|null  $minTime  The minimum time for the validation rule. Expected format is 'H:i'. Default is '09:00'.
     * @return string The validation rule string.
     */
    public static function get(string $date, ?string $maxTime = '22:00', ?string $minTime = '05:30'): string
    {

        if ($date == date('Y-m-d')) {
            return 'date_format:H:i|after_or_equal:'.date('H:i').'|before_or_equal:'.$maxTime;
        } else {
            return 'date_format:H:i|after_or_equal:'.$minTime.'|before_or_equal:'.$maxTime;
        }
    }
}
