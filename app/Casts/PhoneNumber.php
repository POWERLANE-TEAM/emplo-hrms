<?php

namespace App\Casts;

use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber as FormatPhoneNumber;

class PhoneNumber implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // $phoneObj = null;
        // $contactNumber = null;
        // $regionMode = config('app.region_mode');

        // if ($regionMode == 'local') {
        //     $phoneObj = new FormatPhoneNumber($value, 'PH');
        //     $contactNumber = $phoneObj->formatE164();
        // } else {
        //     // $phoneObj->formatInternational();
        // }

        // if ($phoneObj === null) {
        //     $this->reportFormattingError($model, $value, $regionMode);
        // }

        return  $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $phoneObj = null;
        $contactNumber = null;
        $regionMode = config('app.region_mode');

        try {
            if ($regionMode == 'local') {
                $phoneObj = new FormatPhoneNumber($value, 'PH');
                $contactNumber = $phoneObj->formatNational();
            } else {
                // $phoneObj->formatInternational();
            }
        } catch (Exception $e) {
            $this->reportFormattingError($model, $value, $regionMode);
            abort(400, 'Invalid phone number format or is not allowed');
        }

        if ($phoneObj === null) {
            $this->reportFormattingError($model, $value, $regionMode);
        }

        // remove non-numeric characters
        return (preg_replace('/\D/', '', $contactNumber) ?? $value);
    }

    /**
     * Report phone number formatting error.
     */
    private function reportFormattingError(Model $model, mixed $value, string $regionMode): void
    {
        $user = $model->account;
        $userId = $user->account_id ?? 'unknown';
        $userType = $user->account_type ?? 'unknown';
        report(new Exception('Phone number formatting failed for value: ' . $value . ' for user ID: ' . $userId . ' of type: ' . $userType . ' in region mode: ' . $regionMode));
    }
}
