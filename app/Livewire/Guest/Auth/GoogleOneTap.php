<?php

namespace App\Livewire\Guest\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleOneTap extends Component
{
    // public function render()
    // {
    //     return view('livewire.guest.auth.google-one-tap');
    // }

    public function handle(Request $request)
    {
        return $this->oneTapLogin();
    }

    public function oneTapLogin()
    {
        $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
        $payload = $client->verifyIdToken($_POST['credential']);

        if ($payload) {
            $findUser = User::where('email', $payload['email'])->first();

            if ($findUser) {
                dd($payload);
            } else {
                dd($payload);
            }
        } else {
            return false;
        }
    }
}
