<?php

namespace App\Livewire\Auth;

use App\Enums\AccountType;
use App\Enums\PlaceholderString;
use App\Enums\UserStatus;
use App\Models\Guest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class FacebookOAuth extends Component
{
    private $payload;

    public function render()
    {
        return view('livewire.auth.facebook-o-auth');
    }

    public function facebookOauth()
    {
        return Socialite::driver('facebook')->stateless()->redirect();
    }

    public function handleCallback()
    {
        try {

            $this->payload = Socialite::driver('facebook')->stateless()->user();

            $user = User::where('facebook_id', $this->payload->getId())->first();

            if ($user) {

                Auth::login($user);

                return redirect('/hiring');
            }

            if ($this->checkIfEmailExists()) {

                session()->flash('error', 'Something went wrong.');

                return redirect()->intended('/hiring');
            }

            DB::beginTransaction();

            $guest = $this->createGuest();

            $newUser = $this->createUserAccount($guest);

            DB::commit();

            Auth::login($newUser);

            return redirect('/hiring');

        } catch (Exception $e) {

            DB::rollBack();

            report($e);

            session()->flash('error', 'Something went wrong.');

            return redirect()->intended('/hiring');
        }

    }

    private function checkIfEmailExists()
    {
        return User::where('email', $this->payload->getEmail())->exists();
    }

    private function createGuest()
    {
        return Guest::create([
            'first_name' => $this->payload->getName() ?? PlaceholderString::NOT_PROVIDED,
            'middle_name' => null,
            'last_name' => PlaceholderString::NOT_PROVIDED,
        ]);
    }

    private function createUserAccount(Guest $guest)
    {
        $newUser = $guest->account()->create([
            'email' => $this->payload->getEmail() ?? null,
            'account_type' => AccountType::APPLICANT,
            'account_id' => $guest->guest_id,
            'password' => Hash::make(Str::random()),
            'photo' => $this->payload->getAvatar(),
            'facebook_id' => $this->payload->getId(),
            'user_status_id' => UserStatus::ACTIVE,
        ]);

        return $newUser;
    }
}
