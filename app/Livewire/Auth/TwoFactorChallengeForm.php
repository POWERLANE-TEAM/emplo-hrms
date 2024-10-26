<?php

namespace App\Livewire\Auth;

use Livewire\Component;

class TwoFactorChallengeForm extends Component
{
    public function render()
    {
        return view('livewire.auth.two-factor-challenge-form');
    }
}
