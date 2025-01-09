<?php

namespace App\Livewire\Auth\Guests;

use App\Actions\Fortify\CreateNewUser;
use App\Enums\AccountType;
use App\Enums\UserStatus as EnumsUserStatus;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SignUp extends Component
{
    private $job_vacancy;

    public $registered = false;

    public $emailSent = false;

    #[Validate('required|email:rfc,dns,spoof|max:320|unique:users|valid_email_dns')]
    public $email = '';

    #[Validate]
    public $password = '';

    public $password_confirmation = '';

    #[Validate(['accepted'])]
    public $consent = false;

    #[Validate('required|min:2|max:191|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ\' ]+$/')]
    public $first_name = '';

    #[Validate('nullable|min:2|max:191|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \']+$/')]
    public $middle_name = '';

    #[Validate('required|min:2|max:191|regex:/^(?!\s)(?!.*\s{2,})(?!.*\s$)[A-Za-zÑñ \'\\-]+$/')]
    public $last_name = '';

    public function rules()
    {
        return [
            'password' => [
                'required',
                Password::defaults(),
                'confirmed',
            ],
        ];
    }

    #[On('job-hiring-selected')]
    public function showJobVacancy($job_vacancy)
    {

        $this->job_vacancy = $job_vacancy;

        // dd($this->job_vacancy);
    }

    public function store(CreateNewUser $userCreator)
    {

        /* Reference https://www.youtube.com/watch?v=EuqyYQdyBU8 */
        if ((! app()->runningInConsole() && ! app()->environment('local')) || ! app()->environment('testing')) {
            $this->email = trim($this->email);
            $this->first_name = trim($this->first_name);
            $this->middle_name = trim($this->middle_name);
            $this->last_name = trim($this->last_name);
        }

        $this->validate();

        try {
            $newUser = [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'consent' => $this->consent,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name == '' ? null : $this->middle_name,
                'last_name' => $this->last_name,
                'account_type' => AccountType::GUEST->value,
                'user_status' => EnumsUserStatus::ACTIVE->value,
            ];

            $newUserCreated = $userCreator->create($newUser, true);
            $this->dispatch('sign-up-successful');

            $this->registered = true;
        } catch (\Throwable $th) {
            report($th);
            // There is no listener currently for this event
            $this->dispatch('sign-up-error');
        }

        $this->reset();
    }

    public function placeholder()
    {
        $this->dispatch('guest-sign-up-load');

        return view('livewire.placeholder.guests.sign-up');
    }

    public function render()
    {
        return view('livewire.auth.guests.sign-up', ['job_vacancy' => $this->job_vacancy]);
    }

    public function rendered()
    {
        $this->dispatch('guest-sign-up-rendered');
    }
}
