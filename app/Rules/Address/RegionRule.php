<?php

namespace App\Rules\Address;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RegionRule implements ValidationRule
{
    protected $ruleString = 'digits_between:1,100000|exists:regions,region_code';

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
        $rule = ($this->required ? 'required|' : '') . $this->ruleString;

        return $rule;
    }
}