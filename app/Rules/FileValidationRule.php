<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Validator;

class FileValidationRule implements ValidationRule
{
    protected $accepted;
    protected $minSize;
    protected $maxSize;
    protected $required;

    const FILE_SIZES = [
        'xs' => '3072', // 3mb
        'sm' => '5120', // 5mb
        'md' => '10240', // 10mb
        'lg' => '51200', // 50mb
        'xl' => '102400', // 100mb
    ];

    public function __construct(array $accepted, string $minSize = null, string $maxSize = 'sm', bool $required = true)
    {
        $this->accepted = $accepted;
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
        $this->required = $required;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $rules = $this->getRule();

        $validator = Validator::make([$attribute => $value], ['file' => $rules]);

        // if ($validator->fails()) {
        //     $fail('The :attribute does not meet the validation requirements.');
        // }
    }

    public function getRule(): string
    {
        $rule = ($this->required ? 'required|' : '') . 'file|max:' . $this->getMaxFileSize() . '|mimes:' . $this->getAcceptedFileTypes();

        if ($this->minSize) {
            $rule .= '|min:' . $this->getMinFileSize();
        }

        return $rule;
    }
    public function message()
    {
        return 'The :attribute does not meet the validation requirements.';
    }

    protected function getMinFileSize()
    {
        return $this->validateFileSize(self::FILE_SIZES[$this->minSize] ?? $this->minSize);
    }

    protected function getMaxFileSize()
    {
        return self::FILE_SIZES[$this->maxSize] ?? self::FILE_SIZES['sm'];
    }

    protected function getAcceptedFileTypes()
    {
        return implode(',', $this->accepted);
    }

    protected function validateFileSize($size)
    {
        if (is_numeric($size)) {
            return $size;
        }

        if (preg_match('/^\d+$/', $size)) {
            return $size;
        }

        report(new \InvalidArgumentException("Invalid file size: $size"));
        return null;
    }
}
