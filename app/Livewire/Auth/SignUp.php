<?php

namespace App\Livewire\Auth;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Livewire\Attributes\On;
use Livewire\Component;

class SignUp extends Component
{
    private $job_vacancy;

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $consent = false;
    // public $captcha;

    #[On('job-selected')]
    public function showJobVacancy($job_vacancy)
    {

        $this->job_vacancy = collect($job_vacancy[0]);
        $this->job_vacancy = $this->job_vacancy->map(function ($item) {
            if (is_array($item)) {
                return collect($item)->map(function ($nestedItem) {
                    return is_array($nestedItem) ? collect($nestedItem) : $nestedItem;
                });
            }
            return $item;
        });

        // dd($this->job_vacancy['job_details']['job_title']['job_title']);
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
            'consent' => $this->consent,
        ];

        $new_user_created = $userCreate->create($new_user);

        event(new Registered($new_user_created));

        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->consent = false;
    }

    public function placeholder()
    {
        $this->dispatch('guest-sign-up-load');

        return view('livewire.placeholder.sign-up');
    }

    public function render()
    {
        return view('livewire.auth.sign-up', ['job_vacancy' => $this->job_vacancy]);
    }

    public function rendered()
    {
        $this->dispatch('guest-sign-up-rendered');
    }
}
