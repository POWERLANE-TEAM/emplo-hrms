<?php

namespace App\Livewire\Guest\Buttons;

use Livewire\Component;

class SignUp extends Component
{
    public $selectedJob;

    public function showSignUp($job_title)
    {
        dump($job_title);
    }

    public function render()
    {

        return view('livewire.guest.buttons.sign-up');
    }
}
