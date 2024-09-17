<?php

namespace App\Actions\Fortify;

use App\Models\Employee;
use App\Models\User;
use App\Models\UserRole;
use App\Models\UserStatus;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'email' => 'required|email:rfc,dns,spoof|max:191|unique:users|valid_email_dns',
            'password' => [
                'required',
                Password::defaults(),
                'confirmed',
            ],
            'consent' => 'accepted',
        ])->validate();

        return User::create([
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'account_type' => 'applicant',
            'account_id' => Employee::inRandomOrder()->first()->employee_id ?? 1,
            'user_role_id' => UserRole::inRandomOrder()->first()->user_role_id ?? 1,
            'user_status_id' => UserStatus::inRandomOrder()->first()->user_status_id ?? 1,
        ]);
    }
}
