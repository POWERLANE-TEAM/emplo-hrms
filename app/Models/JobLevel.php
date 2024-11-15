<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobLevel extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_level_id';
    
    public $timestamps = false;

    protected $fillable = [
        'job_level',
        'job_level_name',
        'job_level_desc',
    ];

    /**
     * The job titles that belong to the job level.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_details', 'job_level_id', 'job_title_id')
            ->withTimestamps();
    }

    /**
     * The job families that belong to the job level.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobFamilies(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'job_details', 'job_level_id', 'job_family_id')
            ->withTimestamps();
    }

    /**
     * The specific areas that belong to the job level.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */    public function specificAreas(): BelongsToMany
    {
        return $this->belongsToMany(SpecificArea::class, 'job_details', 'job_level_id', 'area_id')
            ->withTimestamps();
    }
}
