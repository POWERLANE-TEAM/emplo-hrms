<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rules\Password;

class Login extends Component
{

    public $email;
    public $password;
    // public $captcha;

    public function store()
    {
        $login_credentials = $this->validate([
            'email' => 'required|email',
            'password' => [
                'required',
                // Password::defaults(),
            ],
        ]);

        Auth::attempt($login_credentials);

        request()->session()->regenerate();

        return redirect('/');

        // Login controller
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
