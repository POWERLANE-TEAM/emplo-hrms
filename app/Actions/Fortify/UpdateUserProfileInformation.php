<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {

        Validator::make($input, [
            'email' => [
                'nullable',
                'string',
                // 'email',
                'max:320',
                Rule::unique('users')->ignore($user->user_id, 'user_id'),
            ],
            'account_type' => [
                'required_with:account_id',
                'string',
                'max:255'
            ],
            'account_id' => [
                'required_with:account_type',
                'integer'
            ],
            'photo' => ['nullable', 'image'],
            'user_status_id' => ['nullable', 'integer'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($input['photo'])) {

            if ($input['photo'] instanceof UploadedFile) {
                $hashedUserId = md5($user->user_id);
                $photoPath = $input['photo']->storeAs(
                    'accounts/' . $input['accountType'] . '/' . $hashedUserId,
                    $input['photo']->hashName(),
                    'public'
                );
                $input['photo'] = $photoPath;
            } else {
                report(new \Exception('Photo must be an instance of UploadedFile'));
            }
        }

        if (
            $input['email'] !== null &&
            $input['email'] != $user->email &&
            $user instanceof MustVerifyEmail
        ) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill(array_filter([
                'email' => $input['email'] ?? null,
                'account_type' => $input['accountType'] ?? null,
                'account_id' => $input['accountId'] ?? null,
                'user_status_id' => $input['userStatusId'] ?? null,
            ], function ($value) {
                return !is_null($value);
            }))
                ->fill([
                    'photo' => $input['photo'] ?? null,
                ])
                ->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    protected function updateVerifiedUser(User $user, array $input): void
    {
        $user->fill(array_filter([
            'email' => $input['email'],
            'account_type' => $input['accountType'] ?? null,
            'account_id' => $input['accountId'] ?? null,

            'user_status_id' => $input['userStatusId'] ?? null,
        ], function ($value) {
            return !is_null($value);
        }))
            ->fill([
                'photo' => $input['photo'] ?? null,
            ])

            // force resetting email_verified_at as it is guarded
            ->forceFill([
                'email_verified_at' => null,
            ])->save();

        $user->sendEmailVerificationNotification();
    }
}
