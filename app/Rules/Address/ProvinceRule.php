<?php

namespace App\Rules\Address;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ProvinceRule implements ValidationRule
{
    protected $ruleString = 'digits_between:1,255';

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
    }

    public function __construct(protected bool $required = true)
    {
        //
    }

    public function getRule(): string
    {
        $rule = ($this->required ? 'required|' : '').$this->ruleString;

        return $rule;
    }
}
