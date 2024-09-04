<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use App\Models\Position;
use Illuminate\Auth\Events\Registered;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class SignUp extends Component
{

    private $position;
    public $email;
    public $password;
    public $password_confirmation;
    public $consent;
    // public $captcha;

    #[On('job-selected')]
    public function showPosition($position)
    {
        $this->placeholder();
        $this->position = new Position($position[0]);
        // dd($position);
    }

    public function store(CreatesNewUsers $userCreate)
    {
        // $this->validate([
        //     'email' => 'required|email:rfc,dns,spoof|max:191|unique:users|valid_email_dns',
        //     'password' => [
        //         'required',
        //         Password::defaults(),
        //         'confirmed'
        //     ],
        //     'consent' => 'accepted',
        // ]);

        // $new_user = User::create([
        //     'email' => $this->email,
        //     'password' => $this->password,
        //     'role' => 'USER',
        // ]);

        /* Reference https://www.youtube.com/watch?v=EuqyYQdyBU8 */

        $new_user = [
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'consent' => $this->consent
        ];

        $new_user_created = $userCreate->create($new_user);

        event(new Registered($new_user_created));
    }

    public function placeholder()
    {
        $this->dispatch('guest-sign-up-load');
        return view('livewire.placeholder.sign-up');
    }

    public function render()
    {
        return view('livewire.auth.sign-up', ['position' => $this->position]);
    }

    public function rendered()
    {
        $this->dispatch('guest-sign-up-rendered');
    }
}
