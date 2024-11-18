<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|max:320|valid_email_dns')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function render()
    {
        return view('livewire.auth.login');
    }
}
