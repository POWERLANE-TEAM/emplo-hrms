<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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
    | Define model accessors(get) and mutators(set) below
    |--------------------------------------------------------------------------
    */

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    protected function middleName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }

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
}
