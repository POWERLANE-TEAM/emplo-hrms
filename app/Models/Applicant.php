<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Applicant extends Model
{
    use HasFactory;

    protected $primaryKey = 'applicant_id';

    protected $guarded = [
        'applicant_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the account of applicant
    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'account');
    }

    // returns the job application of applicant
    public function application(): HasOne
    {
        return $this->hasOne(Application::class, 'applicant_id', 'applicant_id');
    }

    // returns applicants's permanent barangay address
    public function permanentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'permanent_barangay', 'barangay_code');
    }

    // returns applicant's present barangay address
    public function presentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'present_barangay', 'barangay_code');
    }
}
