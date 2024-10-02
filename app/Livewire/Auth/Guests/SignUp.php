<?php

namespace App\Livewire\Auth\Guests;

use App\Models\Applicant;
use App\Models\JobVacancy;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserStatus;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

class SignUp extends Component
{
    private $job_vacancy;

    #[Locked]
    #[Validate('required|in:applicant')]
    const ACCOUNT_TYPE = 'applicant';

    #[Locked]
    #[Validate('required')]
    const ACCOUNT_STATUS = 'active';

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

    // #[Validate('required|digits_between:7,11')]
    #[Validate('required|digits:11')]
    public $contact_number = '';

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

    public function store(CreatesNewUsers $userCreate)
    {
        /* Reference https://www.youtube.com/watch?v=EuqyYQdyBU8 */

        if (empty($this->contact_number)) {
            $this->contact_number = fake()->unique()->numerify('###########');
        }

        if ((!app()->runningInConsole() && !app()->environment('local')) || !app()->environment('testing')) {
            $this->email = trim($this->email);
            $this->first_name = trim($this->first_name);
            $this->middle_name = trim($this->middle_name);
            $this->last_name = trim($this->last_name);
            $this->contact_number = trim($this->contact_number);
        }

        $this->validate();

        $new_user = [
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'consent' => $this->consent,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name == '' ? null : $this->middle_name,
            'last_name' => $this->last_name,
            'contact_number' => $this->contact_number,
            'account_type' => self::ACCOUNT_TYPE,
            'user_status' => self::ACCOUNT_STATUS,
        ];

        $new_user_created = $userCreate->create($new_user, true);

        /* Listen for this livewire event to show email sent modal */
        $this->dispatch('guest-new-user-registered');

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
