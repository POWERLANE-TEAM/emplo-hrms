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

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns job details of the job vacancy
    public function jobDetails(): BelongsTo
    {
        return $this->belongsTo(JobDetail::class, 'job_detail_id', 'job_detail_id');
    }

    // returns applications for the job vacancy
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    // returns the job title through JobDetail model
    public function jobTitle(): HasOneThrough
    {
        return $this->hasOneThrough(JobTitle::class, JobDetail::class, 'job_detail_id', 'job_title_id', 'job_detail_id', 'job_title_id');
    }
}
