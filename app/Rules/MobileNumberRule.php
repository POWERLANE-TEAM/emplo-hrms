<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class MobileNumberRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $regionMode = config('app.region_mode');
        if ($regionMode == 'local') {
            if (! preg_match('/^\d{11}$/', $value)) {
                $fail('The :attribute must be exactly 11 digits.');
            }
        } else {
            if (! preg_match('/^\d{8,15}$/', $value)) {
                $fail('The :attribute must be between 8 and 15 digits.');
            }
        }
    }

    /**
     * Get the validation rule as a string.
     */
    public static function getRule(): string
    {
        $regionMode = config('app.region_mode');

        return $regionMode == 'local' ? 'digits:11' : 'digits_between:8,15';
    }
}
