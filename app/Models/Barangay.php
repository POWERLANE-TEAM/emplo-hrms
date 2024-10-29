<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Barangay extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [];

    /**
     * Get the city of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_code', 'city_code');
    }

    /**
     * Get the province of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    /**
     * Get the region of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_code', 'region_code');
    }

    /**
     * Get the employees who are permanent residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanentEmployeeResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'permanent_barangay');
    }

    /**
     * Get the applicants who are permanent residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanentApplicantResidents(): HasMany
    {
        return $this->hasMany(Applicant::class, 'permanent_barangay');
    }

    /**
     * Get the employees who are present residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presentEmployeeResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'present_barangay');
    }

    /**
     * Get the applicants who are present residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presentApplicantResidents(): HasMany
    {
        return $this->hasMany(Applicant::class, 'present_barangay');
    }
}
