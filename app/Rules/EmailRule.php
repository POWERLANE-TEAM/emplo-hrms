<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;


class EmailRule implements ValidationRule
{

    public function __construct(protected ?bool $shouldStrictlyUnique = true)
    {
        $this->shouldStrictlyUnique = $shouldStrictlyUnique;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rules = self::getRule($this->shouldStrictlyUnique);

        $validator = \Illuminate\Support\Facades\Validator::make(
            [$attribute => $value],
            [$attribute => $rules]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $fail($error);
            }
        }
    }

    /**
     * Get the validation rule as an array.
     *
     * @return array
     */
    public function getRule(): string
    {
        $rules = [
            'email:rfc,dns,spoof',
            'max:320',
            'valid_email_dns',
        ];

        if (auth()->check()) {
            if ($this->shouldStrictlyUnique) {
                $rules[] = 'unique:users,email';
            } else {
                $rules[] = 'unique:users,email,' . auth()->id() . ',user_id';
            }
        }
        
        return implode('|', $rules);
    }
}
