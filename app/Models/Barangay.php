<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'barangay_code';

    protected $fillable = [];

    /**
     * Get the employees who are permanent residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanentEmployeeResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'permanent_barangay', 'barangay_code');
    }

    /**
     * Get the applicants who are permanent residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permanentApplicantResidents(): HasMany
    {
        return $this->hasMany(Applicant::class, 'permanent_barangay', 'barangay_code');
    }

    /**
     * Get the employees who are present residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presentEmployeeResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'present_barangay', 'barangay_code');
    }

    /**
     * Get the applicants who are present residents of the barangay.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function presentApplicantResidents(): HasMany
    {
        return $this->hasMany(Applicant::class, 'present_barangay', 'barangay_code');
    }
}
