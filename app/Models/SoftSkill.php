<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SoftSkill extends Model
{
    use HasFactory;

    protected $primaryKey = 'soft_skill_id';

    protected $fillable = [
        'soft_skill_name',
        'soft_skill_desc',
    ];

    /**
     * The job titles that belong to the soft skill.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_soft_skills', 'job_title_id', 'soft_skill_id')
            ->withTimestamps();
    }
}