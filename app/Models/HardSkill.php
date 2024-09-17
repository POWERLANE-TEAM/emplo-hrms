<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HardSkill extends Model
{
    use HasFactory;

    protected $primaryKey = 'hard_skill_id';

    protected $fillable = [
        'hard_skill_name',
        'hard_skill_desc',
    ];

    // returns job titles of a hard skill
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_hard_skills', 'job_title_id', 'hard_skill_id')
            ->withTimestamps();
    }
}
