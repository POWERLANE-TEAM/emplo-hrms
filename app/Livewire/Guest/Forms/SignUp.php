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
    public $consent;
    // public $captcha;

    #[On('job-selected')]
    public function showPosition($position_id)
    {
        $this->placeholder();
        $this->position = Position::where('position_id', $position_id)->first();
        // dd($this->position);
    }

    public function create()
    {
        $this->validate([
            'email' => 'required|email:rfc,dns,spoof|max:255|unique:users|valid_email_dns',
            'password' => [
                'required',
                Password::defaults()
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
        $this->dispatch('sign-up-loading');
        return view('livewire.placeholder.sign-up');
    }

    public function render()
    {
        return view('livewire.guest.forms.sign-up', ['position' => $this->position]);
    }
}
