<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Get the applicant's full name.
     * 
     * @return string
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => 
                $attributes['last_name'].', '.
                $attributes['first_name'].' '.
                $attributes['middle_name'],
        );
    }

    /**
     * Get the account associated with the applicant.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'account');
    }

    /**
     * Get the job application associated with the applicant.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function application(): HasOne
    {
        return $this->hasOne(Application::class, 'applicant_id', 'applicant_id');
    }

    /**
     * Get the permanent barangay of the applicant.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'permanent_barangay');
    }

    /**
     * Get the present barangay of the applicant.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function presentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'present_barangay');
    }
}
