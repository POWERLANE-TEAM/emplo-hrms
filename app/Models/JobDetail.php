<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_detail_id';

    protected $guarded = [
        'job_detail_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the employee associated with the job detail.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'job_detail_id', 'job_detail_id');
    }

    /**
     * Get the job vacancies associated with the job detail.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'job_detail_id', 'job_detail_id');
    }

    /**
     * Get the job title the job belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Get the job level the job belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobLevel(): BelongsTo
    {
        return $this->belongsTo(JobLevel::class, 'job_level_id', 'job_level_id');
    }

    /**
     * Get the job family the job belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobFamily(): BelongsTo
    {
        return $this->belongsTo(JobFamily::class, 'job_family_id', 'job_family_id');
    }

    /**
     * Get the specific area of the job.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function specificArea(): BelongsTo
    {
        return $this->belongsTo(SpecificArea::class, 'area_id', 'area_id');
    }
}
