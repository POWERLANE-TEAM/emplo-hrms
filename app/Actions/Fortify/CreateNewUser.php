<?php

namespace App\Actions\Fortify;

use App\Enums\AccountType;
use App\Models\Applicant;
use App\Models\Employee;
use App\Models\User;
// use App\Models\UserRole;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

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

        DB::beginTransaction();

        try {

            if ($input['account_type'] == AccountType::APPLICANT->value) {
                $new_account_created = $this->applicant($input);
            } elseif ($input['account_type'] == AccountType::EMPLOYEE->value) {
                $new_account_created = $this->employee($input);
            }

            // account_id from applicant or employee
            $account_id = $new_account_created->applicant_id ?? $new_account_created->employee_id;

            $new_user_created = User::create([
                'account_type' => $input['account_type'],
                'account_id' => $account_id,
                'email' => $input['email'],
                'password' => $input['password'],
                'user_status_id' => $input['user_status'],
            ]);

            DB::commit();

            defer(function () use ($new_user_created) {
                event(new Registered($new_user_created));
            });

            return $new_user_created;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function applicant(array $input): Applicant
    {
        return Applicant::create([
            'first_name' => $input['first_name'],
            'middle_name' => trim($input['middle_name']) === '' ? null : $input['middle_name'],
            'last_name' => $input['last_name'],
            'contact_number' => $input['contact_number'],
        ]);
    }

    private function employee(array $input): Employee
    {
        return Employee::create([
            'first_name' => $input['first_name'],
            'middle_name' => trim($input['middle_name']) === '' ? null : $input['middle_name'],
            'last_name' => $input['last_name'],
            'contact_number' => $input['contact_number'],
        ]);
    }

    private function validate(array $input): bool
    {
        return true;
    }
}
