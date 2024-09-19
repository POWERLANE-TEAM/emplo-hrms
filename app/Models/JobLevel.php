<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobLevel extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_level_id';

    protected $fillable = [
        'job_level',
        'job_level_name',
        'job_level_desc',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns job titles associated with a specific job level
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_details', 'job_level_id', 'job_title_id')
            ->withTimestamps();
    }

    // returns job families associated with a specific job level
    public function jobFamilies(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'job_details', 'job_level_id', 'job_family_id')
            ->withTimestamps();
    }

    // returns available specific areas associated with a specific job level
    public function specificAreas(): BelongsToMany
    {
        return $this->belongsToMany(SpecificArea::class, 'job_details', 'job_level_id', 'area_id')
            ->withTimestamps();
    }
}
