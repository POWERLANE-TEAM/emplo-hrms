<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class JobTitle extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_title_id';

    protected $fillable = [
        'job_title',
        'job_desc',
        'vacancy',
    ];

    /**
     * Get the department that owns the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * The job levels that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobLevels(): BelongsToMany
    {
        return $this->belongsToMany(JobLevel::class, 'job_details', 'job_title_id', 'job_level_id')
            ->withTimestamps();
    }

    /**
     * The job families that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobFamilies(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'job_details', 'job_title_id', 'job_family_id')
            ->withTimestamps();
    }

    /**
     * The specific areas that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specificAreas(): BelongsToMany
    {
        return $this->belongsToMany(SpecificArea::class, 'job_details', 'job_title_id', 'area_id')
            ->withTimestamps();
    }

    /**
     * The soft skills that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function softSkills(): BelongsToMany
    {
        return $this->belongsToMany(SoftSkill::class, 'job_soft_skills', 'job_title_id', 'soft_skill_id')
            ->withTimestamps();
    }

    /**
     * The hard skills that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function hardSkills(): BelongsToMany
    {
        return $this->belongsToMany(HardSkill::class, 'job_hard_skills', 'job_title_id', 'hard_skill_id')
            ->withTimestamps();
    }

    /**
     * The education requirements that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function educationRequirements(): BelongsToMany
    {
        return $this->belongsToMany(EducationRequirement::class, 'job_education_requirements', 'job_title_id', 'education_req_id')
            ->withTimestamps();
    }

    /**
     * The experience requirements that belong to the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function experienceRequirements(): BelongsToMany
    {
        return $this->belongsToMany(ExperienceRequirement::class, 'job_education_requirements', 'job_title_id', 'experience_req_id')
            ->withTimestamps();
    }
}
