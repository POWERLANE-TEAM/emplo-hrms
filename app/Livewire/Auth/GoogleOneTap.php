<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use App\Traits\GoogleCallback;
use Illuminate\Support\Facades\Auth;

class GoogleOneTap extends Component
{
    use GoogleCallback;

    public function render()
    {
        return view('livewire.auth.google-one-tap');
    }

    public function handleCallback(Request $request)
    {
        $client = new \Google_Client(['client_id' => config('services.google.client_id')]);

        $credential = $request->input('credential');
        
        $payload = $client->verifyIdToken($credential);

        if ($payload) {

            $user = User::where('google_id', $payload['sub'])->first();

            if ($user) {

                Auth::login($user);

                return redirect('hiring');

            } else {

                $new_user = $this->oneTapWithGoogle($payload);

                if(! $new_user) {

                    //

                } else {

                    Auth::login($new_user);

                    return redirect('hiring');
                }
            }
        }
    }
}
