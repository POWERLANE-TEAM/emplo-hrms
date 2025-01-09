<?php

namespace App\Models;

use App\Casts\PhoneNumber;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'contact_number' => PhoneNumber::class,
        ];
    }

    /**
     * Get the applicant's full name.
     *
     * @return string
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => $attributes['last_name'] . ', ' .
                $attributes['first_name'] . ' ' .
                $attributes['middle_name'],
        );
    }

    /**
     * Get the account associated with the applicant.
     */
    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'account');
    }

    /**
     * Get the job application associated with the applicant.
     */
    public function application(): HasOne
    {
        return $this->hasOne(Application::class, 'applicant_id', 'applicant_id');
    }

    /**
     * Get the permanent barangay of the applicant.
     */
    public function permanentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'permanent_barangay');
    }

    /**
     * Get the present barangay of the applicant.
     */
    public function presentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'present_barangay');
    }

    /**
     * Get the skills of the applicant.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(ApplicantSkill::class, 'applicant_id', 'applicant_id');
    }
    
    /**
     * Get the educational attainments of the applicant.
     */
    public function educations(): HasMany
    {
        return $this->hasMany(ApplicantEducation::class, 'applicant_id', 'applicant_id');
    }

    /**
     * Get the work experiences of the applicant.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(ApplicantExperience::class, 'applicant_id', 'applicant_id');
    }
}
