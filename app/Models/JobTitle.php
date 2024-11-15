<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobTitle extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_title_id';

    protected $fillable = [
        'job_title',
        'job_desc',
        'department_id',
        'vacancy',
    ];

    /**
     * Get the department that owns the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * The job levels that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobLevels(): BelongsToMany
    {
        return $this->belongsToMany(JobLevel::class, 'job_details', 'job_title_id', 'job_level_id')
            ->withTimestamps();
    }

    /**
     * The job families that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobFamilies(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'job_details', 'job_title_id', 'job_family_id')
            ->withTimestamps();
    }

    /**
     * The specific areas that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specificAreas(): BelongsToMany
    {
        return $this->belongsToMany(SpecificArea::class, 'job_details', 'job_title_id', 'area_id')
            ->withTimestamps();
    }

    /**
     * Get the qualifications associated with the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualifications(): HasMany
    {
        return $this->hasMany(JobTitleQualification::class, 'job_title_id', 'job_title_id');
    }
}
