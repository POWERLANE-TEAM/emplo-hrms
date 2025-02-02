<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Guest;
use App\Enums\UserStatus;
use App\Enums\AccountType;
use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use App\Enums\PlaceholderString;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait GoogleCallback
{
    private User $newUser;

    /**
     * Save Google user payload into the database.
     * 
     * @param array<string, null> $payload
     * @return \App\Models\User
     */
    public function saveGooglePayload(array $payload)
    {
        DB::transaction(function () use ($payload) {
            activity()->withoutLogs(function () use ($payload) {
                $payload = collect($payload);
                $guest = $this->createGuest($payload);
                $this->newUser = $this->createUserAccount($guest, $payload);                
            });
        });

        activity()
            ->useLog(ActivityLogName::AUTHENTICATION->value)
            ->withProperties(['user' => $this->newUser])
            ->event('created')
            ->log(__('A new guest user has registered via Google'));            

        return $this->newUser;
    }

    /**
     * Create a new guest type of user.
     * 
     * @param  \Illuminate\Support\Collection $payload
     * @return \App\Models\Guest
     */
    private function createGuest(Collection $payload)
    {
        return Guest::create([
            'first_name' => $payload->get('given_name') ?? PlaceholderString::NOT_PROVIDED,
            'middle_name' => null,
            'last_name' => $payload->get('family_name') ?? PlaceholderString::NOT_PROVIDED,
        ]);
    }

    /**
     * Create the user account from Google payload.
     * 
     * @param \App\Models\Guest $guest
     * @param \Illuminate\Support\Collection $payload
     * @return \App\Models\User
     */
    private function createUserAccount(Guest $guest, Collection $payload)
    {
        return $guest->account()->create([
            'email' => $payload->get('email'),
            'account_type' => AccountType::GUEST->value,
            'account_id' => $guest->guest_id,
            'password' => Str::password(),
            'photo' => $payload->get('picture') ?? null,
            'google_id' => $payload->get('sub'),
            'user_status_id' => UserStatus::ACTIVE,
        ]);
    }
}
