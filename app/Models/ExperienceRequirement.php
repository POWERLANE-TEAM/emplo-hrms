<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ExperienceRequirement extends Model
{
    use HasFactory;

    protected $primaryKey = 'experience_req_id';

    protected $fillable = [
        'job_title',
        'years_of_exp',
        'exp_desc',
    ];

    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_experience_requirements', 'job_title_id', 'experience_req_id')
            ->withTimestamps();
    }
}
