<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Notifications\PasswordChangeAttemptNotification;
use App\Notifications\PasswordResetNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        $user->loadMissing('account');

        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->after(function ($validator) use ($user, $input) {
            if (Hash::check($input['password'], $user->password)) {
                $validator->errors()->add('password', 'Your new password cannot be the same as your current password.');
                $user->notify(new PasswordChangeAttemptNotification);
            }
        })->validate();

        $user->forceFill([
            'password' => $input['password'],
        ])->save();

        $user->notify(new PasswordResetNotification);
    }
}
