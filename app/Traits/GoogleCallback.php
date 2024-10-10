<?php

namespace App\Traits;

use App\Enums\AccountType;
use App\Enums\PlaceholderString;
use App\Enums\UserStatus;
use App\Models\Guest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait GoogleCallback
{
    public function saveGooglePayload(array $payload)
    {
        $payload = collect($payload);

        $guest = $this->createGuest($payload);

        $new_user = $this->createUserAccount($guest, $payload);

        return $new_user;
    }

    private function createGuest($payload)
    {
        return Guest::create([
            'first_name' => $payload->get('given_name') ?? PlaceholderString::NOT_PROVIDED,
            'middle_name' => null,
            'last_name' => $payload->get('family_name') ?? PlaceholderString::NOT_PROVIDED,
        ]);
    }

    private function createUserAccount(Guest $guest, $payload)
    {
        return $guest->account()->create([
            'email' => $payload->get('email'),
            'account_type' => AccountType::GUEST,
            'account_id' => $guest->guest_id,
            'password' => Hash::make(Str::random()),
            'photo' => $payload->get('picture') ?? null,
            'google_id' => $payload->get('sub'),
            'user_status_id' => UserStatus::ACTIVE,
        ]);
    }
}
