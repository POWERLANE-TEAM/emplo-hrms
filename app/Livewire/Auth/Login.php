<?php

namespace App\Livewire\Auth;

use App\Http\Controllers\SessionController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Livewire\Component;
use Livewire\Attributes\On;
use Laravel\Socialite\Facades\Socialite;

class Login extends Component
{

    public $email = '';
    public $password = '';
    public $remember = false;
    // public $captcha;

    // public function store(SessionController $session_controller)
    // {
    //     $login_credentials = $this->validate([
    //         'email' => 'required|email',
    //         'password' => [
    //             'required',
    //             // Password::defaults(),
    //         ],
    //     ]);

    //     $session_controller->store($login_credentials);
    // }
    public function store(AuthenticatedSessionController $session_controller)
    {

        $login_credentials = $this->validate([
            'email' => 'required|email',
            'password' => [
                'required',
            ],
        ]);

        if (!Auth::validate($login_credentials)) {

            $this->password = '';
            throw ValidationException::withMessages([
                'credentials' => 'Incorrect credentials or user does not exist.'
            ]);
        }

        $login_request = new LoginRequest();

        $login_request->merge([
            'email' => $this->email,
            'password' => $this->password,
            'remember' => $this->remember,
        ]);

        $session_controller->store($login_request);
    }

    // public function placeholder()
    // {
    //     $this->dispatch('guest-sign-up-load');
    //     return view('livewire.placeholder.sign-up');
    // }

    public function render()
    {
        return view('livewire.auth.login');
    }

    public function rendered()
    {
        // $this->dispatch('guest-sign-up-rendered');
    }
}
