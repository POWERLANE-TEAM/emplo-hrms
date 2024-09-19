<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SpecificArea extends Model
{
    use HasFactory;

    protected $primaryKey = 'area_id';

    protected $fillable = [
        'area_name',
        'area_manager',
        'area_desc',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns area manager of a specific area
    public function areaManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'area_manager', 'employee_id');
    }

    // returns job titles existing to a specific area
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_details', 'area_id', 'job_title_id')
            ->withTimestamps();
    }

    // returns job levels existing to a specific area
    public function jobLevels(): BelongsToMany
    {
        return $this->belongsToMany(JobLevel::class, 'job_details', 'area_id', 'job_level_id')
            ->withTimestamps();
    }

    // returns job familes existing to a specific area
    public function jobFamilies(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'job_details', 'area_id', 'job_family_id')
            ->withTimestamps();
    }
}
