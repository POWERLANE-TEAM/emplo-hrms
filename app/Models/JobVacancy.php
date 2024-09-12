<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // returns job details of a particular job vacancy
    public function jobDetails(): BelongsTo
    {
        return $this->belongsTo(JobDetail::class, 'job_detail_id', 'job_detail_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_vacancy_id', 'job_vacancy_id');
    }
}
