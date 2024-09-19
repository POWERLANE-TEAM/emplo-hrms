<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EducationRequirement extends Model
{
    use HasFactory;

    protected $primaryKey = 'education_req_id';

    protected $fillable = [
        'education_level',
        'study_field',
    ];

    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_education_requirements', 'job_title_id', 'education_req_id')
            ->withTimestamps();
    }
}
