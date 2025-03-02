<?php

namespace App\Traits;

use App\Enums\AccountType;

trait Applicant
{
    public static function hasApplication(?bool $isTerminate = false)
    {
        $user = auth()->user();
        if ($user->account_type == AccountType::APPLICANT->value && $user->account->application()->exists()) {
            if ($isTerminate) {
                abort(403, 'You have already submitted an application.');
            }

            return true;
        }
    }

    /**
     * Check if the current user is an applicant or not.
     * Employee with enough permission can proceed.
     *
     * @param  bool|null  $isTerminate  Optional. If true, aborts the request with a 403 status code.
     *                                  I
     * @return bool|null Returns true if the user is an employee without the required permission
     *                   and $isTerminate is false or null.
     */
    public static function applicantOrYet(?bool $additionalCheck = false, ?bool $isTerminate = false)
    {
        if (auth()->user()->account_type == AccountType::EMPLOYEE->value && $additionalCheck) {
            if ($isTerminate) {
                abort(403, 'You are not allowed to access this page.');
            }

            return true;
        }
    }
}
