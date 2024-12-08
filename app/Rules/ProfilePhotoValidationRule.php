<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class ProfilePhotoValidationRule extends PhotoValidationRule
{
    // Should  be strict by adding ratio=3/2,4/3,3/4,2/3,1/1,
    // But it doent accept photo
    protected $dimensionsRule;

    public function __construct(string $minSize = null, string $maxSize = 'sm', bool $required = true, string $dimensionsRule = 'dimensions:min_width=160,min_height=160,max_width=2160,max_height=2160')
    {
        parent::__construct($minSize, $maxSize, $required);
        $this->dimensionsRule = $dimensionsRule;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rules = $this->getRule() . '|' . $this->dimensionsRule;

        $validator = Validator::make([$attribute => $value], ['file' => $rules]);

        if ($validator->fails()) {
            $fail('The :attribute must have a valid aspect ratio and dimensions between 160x160 and 2160x2160 pixels.');
        }
    }

    public function messages()
    {
        // return [
        //     'file.dimensions' => 'The :attribute must have a valid aspect ratio and dimensions between 160x160 and 2160x2160 pixels.',
        // ];
    }

    public function getDimensionsRule()
    {
        return '|' . $this->dimensionsRule;
    }
}
