<?php

namespace App\Traits;

use App\Enums\PlaceholderString;
use App\Enums\UserStatus;
use App\Models\Applicant;
use App\Enums\AccountType;
use Illuminate\Support\Facades\Hash;

trait GoogleCallback
{
    public function signInWithGoogle($payload)
    {
        return $this->createUser([
            'email' => $payload->email,
            'password' => Hash::make($payload->id),
            'google_id' => $payload->id,
            'applicant_data' => $payload->user,
        ]);
    }

    public function oneTapWithGoogle(array $payload)
    {
        return $this->createUser([
            'email' => $payload['email'],
            'password' => Hash::make($payload['sub']),
            'google_id' => $payload['sub'],
            'applicant_data' => $payload,
        ]);
    }

    private function createUser(array $payload)
    {
        // creates the applicant record
        $new_applicant = Applicant::create([
            'first_name' => $payload['applicant_data']['given_name'] ?? PlaceholderString::NOT_PROVIDED,
            'middle_name' => null,
            'last_name' => $payload['applicant_data']['family_name'] ?? PlaceholderString::NOT_PROVIDED,
            'contact_number' => PlaceholderString::UNAVAILABLE,
            'education' => null,
            'experience' => null,
        ]);

        // creates the account of the applicant
        $new_user = $new_applicant->account()->create([
            'email' => $payload['email'],
            'account_type' => AccountType::APPLICANT,
            'account_id' => $new_applicant->user_id,
            'password' => $payload['password'],
            'google_id' => $payload['google_id'],
            'user_status_id' => UserStatus::ACTIVE,
        ]);

        return $new_user;
    }
}
