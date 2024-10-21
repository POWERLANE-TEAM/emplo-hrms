<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class JobVacancy extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_vacancy_id';

    protected $guarded = [
        'job_vacancy_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the job detail that owns the job vacancy.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobDetail(): BelongsTo
    {
        return $this->belongsTo(JobDetail::class, 'job_detail_id', 'job_detail_id');
    }

    /**
     * Get the job applications associated with the job vacancy.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    /**
     * Get the job title through the job detail.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function jobTitle(): HasOneThrough
    {
        return $this->hasOneThrough(JobTitle::class, JobDetail::class, 'job_detail_id', 'job_title_id', 'job_detail_id', 'job_title_id');
    }
}
