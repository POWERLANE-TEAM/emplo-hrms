<?php

namespace App\Livewire\Auth;

use Exception;
use App\Models\User;
use App\Models\Guest;
use Livewire\Component;
use App\Enums\UserStatus;
use App\Enums\AccountType;
use Illuminate\Support\Str;
use App\Enums\PlaceholderString;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

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
            
            $new_user = $this->createUserAccount($guest);

            DB::commit();

            Auth::login($new_user);

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
        $new_user = $guest->account()->create([
            'email' => $this->payload->getEmail(),
            'account_type' => AccountType::APPLICANT,
            'account_id' => $guest->user_id,
            'password' => Hash::make(Str::random()), // random placeholder shit
            'facebook_id' => $this->payload->getId(),
            'user_status_id' => UserStatus::ACTIVE,
        ]);

        return $new_user;
    }
}
