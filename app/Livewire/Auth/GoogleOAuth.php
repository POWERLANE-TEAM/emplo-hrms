<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Traits\GoogleCallback;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Component;

class GoogleOAuth extends Component
{
    use GoogleCallback;

    public function googleOauth()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    public function googleCallback()
    {
        try {

            $google_user = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $google_user->id)
                ->orWhere('email', $google_user->getEmail())
                ->first();

            if ($user) {

                Auth::login($user);

                return redirect('/hiring');
            }

            DB::beginTransaction();

            $new_user = $this->saveGooglePayload($google_user->user);

            DB::commit();

            if (! $new_user) {

                session()->flash('error', 'Something went wrong.');

                return redirect('/');
            }

            Auth::login($new_user);

            return redirect('/hiring');

        } catch (Exception $e) {

            DB::rollBack();

            report($e);

            return redirect()->intended('/');
        }
    }

    public function render()
    {
        return view('livewire.auth.google-sign-up');
    }
}
