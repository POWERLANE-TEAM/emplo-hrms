<?php

namespace App\Traits;

use App\Models\Guest;
use App\Enums\UserStatus;
use App\Enums\AccountType;
use Illuminate\Support\Str;
use App\Enums\PlaceholderString;
use Illuminate\Support\Facades\Hash;

trait GoogleCallback
{
    public function saveGooglePayload($payload)
    {
        // converts to object if payload is array
        $payload = is_array($payload) ? (object) $payload : $payload;

        $guest = $this->createGuest($payload);

        $new_user = $this->createUserAccount($payload, $guest);

        return $new_user;
    }

    private function createGuest($payload)
    {
        return Guest::create([
            'first_name' => $payload->given_name ?? PlaceholderString::NOT_PROVIDED,
            'middle_name' => null,
            'last_name' => $payload->family_name ?? PlaceholderString::NOT_PROVIDED,
        ]);
    }

    private function createUserAccount($payload, $guest)
    {
        return $guest->account()->create([
            'email' => $payload->email,
            'account_type' => AccountType::GUEST,
            'account_id' => $guest->guest_id,
            'password' => Hash::make(Str::random()),
            'photo' => $payload->picture ?? null,
            'google_id' => $payload->sub,
            'user_status_id' => UserStatus::ACTIVE,
        ]);
    }
}
