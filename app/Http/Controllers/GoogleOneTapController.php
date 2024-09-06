<?php

namespace App\Http\Controllers;

use Google\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleOneTapController extends Controller
{

    public function handleCallback(Request $request) {
        return $this->oneTapLogin();
    }

    public function oneTapLogin() {
        $client = new \Google_Client(['client_id' => config('services.google.client_id')]);
        $payload = $client->verifyIdToken($_POST['credential']);

        if($payload) {
            $findUser = User::where('email', $payload['email'])->first();
            
            if($findUser) {
                return Auth::loginUsingId($findUser->id);
            } else {
                return $payload;
            }
        } else {
            return false;
        }
    }
}
