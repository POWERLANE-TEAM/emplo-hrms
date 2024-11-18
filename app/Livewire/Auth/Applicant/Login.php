<?php

namespace App\Livewire\Auth\Applicant;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function render()
    {
        return view('livewire.auth.applicant.login');
    }
}
