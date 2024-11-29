<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class FileForm extends Form
{

    public $file;

    public $maxSize;

    public $minSize;

    public $accepted;

    public $required;

    const FILE_SIZES = [
        'xs' => '3072', // 3mb
        'sm' => '5120', // 5mb
        'md' => '10240', // 10mb
        'lg' => '51200', // 50mb
        'xl' => '102400', // 100mb
    ];

    const IMAGE_MIME_TYPES = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    public function mount(array $accepted,  bool $isImage, string $minSize = null, string $maxSize = 'sm', bool $required = true)
    {
        $this->accepted = $isImage ? self::IMAGE_MIME_TYPES : $accepted;
        $this->accepted = $accepted;
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
        $this->required = $required;
    }

    public function rules()
    {
        $rules = [
            'file' => ($this->required ? 'required|' : '') . 'bail|file|max:' . $this->getMaxFileSize() . '|mimes:' . $this->getAcceptedFileTypes(),
        ];

        if ($this->minSize) {
            $rules['file'] .= '|min:' . $this->getMinFileSize();
        }

        return $rules;
    }

    public function setToImageMode()
    {
        $this->accepted = self::IMAGE_MIME_TYPES;
    }

    public function setToHasImageMode()
    {
        $this->accepted = array_merge($this->accepted, self::IMAGE_MIME_TYPES);
    }

    public function setAccepted(array $accepted)
    {
        $this->accepted = $accepted;
    }

    public function setMinSize(string $minSize = null)
    {
        $this->minSize = $minSize;
    }

    public function setMaxSize(string $maxSize = 'sm')
    {
        $this->maxSize = $maxSize;
        return self::FILE_SIZES[$this->maxSize];
    }

    public function setRequired(bool $required = true)
    {
        $this->required = $required;
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
