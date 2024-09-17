<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_detail_id';

    protected $fillable = [];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns job vacancies of the job detail
    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'job_detail_id', 'job_detail_id');
    }

    // returns job details of a particular job vacancy
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }
}
