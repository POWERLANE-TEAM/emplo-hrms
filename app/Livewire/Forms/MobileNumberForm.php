<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class MobileNumberForm extends Form
{
    #[Validate(as: 'mobile number')]
    public $mobileNum;

    protected function rules()
    {
        return [
            'mobileNum' => [
                'bail',
                (config('app.region_mode') == 'local' ? 'digits:11' : 'digits_between:8,15'),
            ],
        ];
    }
}
