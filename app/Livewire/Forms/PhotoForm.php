<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class PhotoForm extends FileForm
{
    const IMAGE_MIME_TYPES = [
        'jpg',
        'jpeg',
        'png',
        'webp'
    ];

    public function mount(array $accepted, string $minSize = null, string $maxSize = 'sm', bool $required = true)
    {
        parent::mount($accepted, $minSize, $maxSize, $required);
        $this->accepted = self::IMAGE_MIME_TYPES;
    }

    public function setToImageMode()
    {
        $this->accepted = self::IMAGE_MIME_TYPES;
    }

    public function setToHasImageMode()
    {
        $this->accepted = array_merge($this->accepted, self::IMAGE_MIME_TYPES);
    }
}
