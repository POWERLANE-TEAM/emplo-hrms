<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
