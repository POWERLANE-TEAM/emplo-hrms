<?php

namespace App\Livewire\Auth\Admins;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public $remember = false;

    public function store(AuthenticatedSessionController $session_controller)
    {

        $login_attempt = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (! Auth::validate($login_attempt)) {

            $this->password = '';
            throw ValidationException::withMessages([
                'credentials' => 'Incorrect credentials or user does not exist.',
            ]);
        }

        $login_request = new LoginRequest();

        $login_request->merge($login_attempt);

        $session_controller->store($login_request, $this->remember);
    }
    public function render()
    {
        return view('livewire.auth.admins.login');
    }
}
