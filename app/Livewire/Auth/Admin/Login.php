<?php

namespace App\Livewire\Auth\Admin;

use Livewire\Component;
use Livewire\Attributes\Validate;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public function render()
    {
        return view('livewire.auth.admin.login');
    }
}
