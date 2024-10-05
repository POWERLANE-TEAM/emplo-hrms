<?php

namespace App\Livewire\Auth;

use App\Traits\GoogleCallback;
use App\Models\User;
use Livewire\Component;;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleOAuth extends Component
{
    use GoogleCallback;

    public function googleOauth()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback()
    {
        $google_user = Socialite::driver('google')->stateless()->user();

        $user = User::where('google_id', $google_user->id)->first();

        if ($user) {

            Auth::login($user);

            return redirect('/hiring');

        } else {

            $new_user = $this->signInWithGoogle($google_user);

            if (! $new_user) {

                //

            } else {

                Auth::login($new_user);

                return redirect('/hiring');                    
            }
        }
    }

    public function render()
    {
        return view('livewire.auth.google-sign-up');
    }
}
