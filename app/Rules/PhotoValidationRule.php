<?php

namespace App\Rules;

class PhotoValidationRule extends FileValidationRule
{
    const IMAGE_MIME_TYPES = [
        'jpeg',
        'png',
        'gif',
        'bmp',
        'svg',
        'webp',
    ];

    public function __construct(?string $minSize = null, string $maxSize = 'sm', bool $required = true)
    {

        parent::__construct(self::IMAGE_MIME_TYPES, $minSize, $maxSize, $required);
    }

    public function setToImageMode()
    {
        $this->accepted = self::IMAGE_MIME_TYPES;
    }

    public function setToHasImageMode(array $accepted)
    {
        $this->accepted = array_merge($this->accepted, $accepted);
    }
}
