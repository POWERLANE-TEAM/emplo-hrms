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

    public function store(AuthenticatedSessionController $sessionController)
    {
        $loginAttempt = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (! Auth::validate($loginAttempt)) {

            $this->password = '';

            throw ValidationException::withMessages([
                'credentials' => 'Incorrect credentials or user does not exist.',
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
