<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [];

    /**
     * Get the city of the barangay.
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_code', 'city_code');
    }

    /**
     * Get the province of the barangay.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    /**
     * Get the region of the barangay.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_code', 'region_code');
    }

    /**
     * Get the employees who are permanent residents of the barangay.
     */
    public function permanentEmployeeResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'permanent_barangay');
    }

    /**
     * Get the applicants who are permanent residents of the barangay.
     */
    public function permanentApplicantResidents(): HasMany
    {
        return $this->hasMany(Applicant::class, 'permanent_barangay');
    }

    /**
     * Get the employees who are present residents of the barangay.
     */
    public function presentEmployeeResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'present_barangay');
    }

    /**
     * Get the applicants who are present residents of the barangay.
     */
    public function presentApplicantResidents(): HasMany
    {
        return $this->hasMany(Applicant::class, 'present_barangay');
    }
}
