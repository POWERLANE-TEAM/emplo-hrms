<?php

namespace App\Rules;

use App\Models\InterviewRating;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidApplicantInterviewRating implements ValidationRule
{
    protected ?string $attributeName;

    public function __construct(?string $attributeName = null)
    {
        $this->attributeName = $attributeName;
    }

    public static function getRule(): string
    {
        return 'exists:interview_ratings,rating_id';
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! InterviewRating::where('rating_id', $value)->exists()) {
            $fail('This '.($this->attributeName ?? $attribute).' is invalid.');
        }
    }
}
