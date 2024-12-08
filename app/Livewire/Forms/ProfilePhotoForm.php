<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class ProfilePhotoForm extends PhotoForm
{
    // Should  be strict by adding ratio=3/2,4/3,3/4,2/3,1/1,
    // But it doent accept photo
    protected $dimensionsRule = 'dimensions:min_width=160,min_height=160,max_width=2160,max_height=2160';

    public function rules()
    {
        $rules = parent::rules();

        $rules['file'] .= '|' . $this->dimensionsRule;

        return $rules;
    }

    public function messages()
    {
        return [
            'file.dimensions' => 'The :attribute must have a valid aspect ratio and dimensions between 160x160 and 2160x2160 pixels.',
        ];
    }

    public function getDimensionsRule()
    {
        return '|' . $this->dimensionsRule;
    }
}
