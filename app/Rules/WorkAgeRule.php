<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class WorkAgeRule extends ScheduleDateRule implements ValidationRule
{
    protected $minDate;

    protected $maxDate;

    protected $message;

    public function __construct()
    {
        $timezone = config('app.server_timezone');
        $dateTimeZone = new \DateTimeZone($timezone);

        $this->minDate = (new \DateTime('now', $dateTimeZone))->modify('-65 years')->format('Y-m-d');
        $this->maxDate = (new \DateTime('now', $dateTimeZone))->modify('-18 years')->format('Y-m-d');
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $timezone = config('app.server_timezone');
        $dateTimeZone = new \DateTimeZone($timezone);

        try {
            $dateValue = new \DateTime($value, $dateTimeZone);
        } catch (\Exception $e) {
            $fail('The '.$attribute.' must be a valid date.');

            return;
        }

        $minDate = new \DateTime($this->minDate, $dateTimeZone);
        $maxDate = new \DateTime($this->maxDate, $dateTimeZone);

        if ($dateValue < $minDate) {
            $fail($this->message ?: 'Must be younger than 65 years old.');
        } elseif ($dateValue > $maxDate) {
            $fail($this->message ?: 'Must be at least 18 years old.');
        }
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMinDate(): string
    {
        return $this->minDate;
    }

    public function getMaxDate(): string
    {
        return $this->maxDate;
    }
}
