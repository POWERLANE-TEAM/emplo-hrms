<?php

namespace App\Actions\Fortify;

use App\Enums\AccountType;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
// use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    protected $input;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input, bool $wasValidated = false): User
    {

        if (! $wasValidated) {
            if (! $this->validate($input)) {
                // throw exception
            }
        }

        $this->input = $input;

        DB::beginTransaction();

        try {

            if ($input['account_type'] == AccountType::GUEST->value) {
                $newGuestAcc = $this->guest($input);
            }

            $newUser = $this->user($newGuestAcc);
            Log::info(request()->session()->getId());
            $newUser['session'] = request()->session()->getId();

            DB::commit();

            // Triggers email verification email
            event(new Registered($newUser));

            return $newUser;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function guest(array $input): Guest
    {
        return Guest::create([
            'first_name' => $input['first_name'],
            'middle_name' => trim($input['middle_name']) === '' ? null : $input['middle_name'],
            'last_name' => $input['last_name'],
        ]);
    }

    private function user($newAcc): User
    {
        return $newAcc->account()->create([
            'account_type' => $this->input['account_type'],
            'account_id' => $newAcc->account_id,
            'email' => $this->input['email'],
            'password' => $this->input['password'],
            'user_status_id' => $this->input['user_status'],
        ]);
    }

    private function validate(array $input): bool
    {
        return true;
    }
}
