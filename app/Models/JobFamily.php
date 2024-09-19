<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobFamily extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_family_id';

    protected $fillable = [
        'job_family_name',
        'job_family_desc',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns job titles associated with a specific job family
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_details', 'job_family_id', 'job_title_id')
            ->withTimestamps();
    }

    // returns job levels associated with a specific job family
    public function jobLevels(): BelongsToMany
    {
        return $this->belongsToMany(JobLevel::class, 'job_details', 'job_family_id', 'job_level_id')
            ->withTimestamps();
    }

    // returns available areas associated with a specific job family
    public function specificAreas(): BelongsToMany
    {
        return $this->belongsToMany(SpecificArea::class, 'job_details', 'job_family_id', 'area_id')
            ->withTimestamps();
    }
}
