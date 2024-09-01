<?php

namespace App\Livewire\Guest\Forms;

use App\Models\Position;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\On;
use Livewire\Component;

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
        $this->position = new Position($position);
        // dd($this->position);
    }

    public function create()
    {
        $this->validate([
            'email' => 'required|email:rfc,dns,spoof|max:191|unique:users|valid_email_dns',
            'password' => [
                'required',
                Password::defaults(),
                'confirmed'
            ],
            'consent' => 'accepted',
        ]);

        User::create([
            'email' => $this->email,
            'password' => $this->password,
            'role' => 'USER',
        ]);
    }

    public function placeholder()
    {
        $this->dispatch('guest-sign-up-load');
        return view('livewire.placeholder.sign-up');
    }

    public function render()
    {
        return view('livewire.guest.forms.sign-up', ['position' => $this->position]);
    }

    public function rendered()
    {
        $this->dispatch('guest-sign-up-rendered');
    }
}
