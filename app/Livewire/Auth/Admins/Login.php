<?php

namespace App\Livewire\Auth\Admins;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

class Login extends Component
{
    #[Validate('required|email')]
    public $email = '';

    #[Validate('required')]
    public $password = '';

    public function store(AuthenticatedSessionController $sessionController)
    {
        $this->validate();

        $loginAttempt = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (! Auth::validate($loginAttempt)) {

            $this->reset('password');

            throw ValidationException::withMessages([
                'credentials' => [__('Incorrect credentials or user does not exist.')],
            ]);
        }
        $loginRequest = new LoginRequest;
        $loginRequest->merge($loginAttempt);
        $loginRequest->setLaravelSession(app('session')->driver());

        $sessionController->store($loginRequest);
    }

    public function render()
    {
        return view('livewire.auth.admins.login');
    }
}
