<?php

namespace App\Livewire\Auth\Admin;

use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    #[Locked]
    public $loginMessage;

    public function render()
    {
        return view('livewire.auth.admin.login');
    }
}
