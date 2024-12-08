<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class EmailForm extends Form
{
    #[Validate]
    public $email;

    protected $shouldStrictlyUnique = true;

    public function rules()
    {
        $rules = [
            'email' => [
                'required',
                'email:rfc,dns,spoof',
                'max:320',
                'valid_email_dns'
            ],
        ];

        if ($this->shouldStrictlyUnique) {
            $rules['email'][] = 'unique:users';
        } else
            $rules['email'][] = Rule::unique('users')->ignore(auth()->user()->user_id, 'user_id');


        return $rules;
    }

    public function getShouldStrictlyUnique()
    {
        return $this->shouldStrictlyUnique;
    }

    public function setShouldStrictlyUnique(?bool $value = true)
    {
        $this->shouldStrictlyUnique = $value;
    }
}
