<?php

namespace App\Rules\Address;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AddressRule implements ValidationRule
{
    protected $ruleString = 'max:511|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[a-zA-Z0-9\s,.-]+$/';

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
