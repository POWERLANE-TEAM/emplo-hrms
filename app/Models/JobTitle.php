<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobTitle extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'job_title_id';

    protected $guarded = [
        'job_title_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the department that owns the job title.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    /**
     * Get the employees associated with the job title through **EmployeeJobDetail** model.
     */
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, EmployeeJobDetail::class, 'job_title_id', 'employee_id', 'job_title_id', 'employee_id');
    }

    /**
     * Get the employee job details associated with the job title.
     */
    public function jobDetails(): HasMany
    {
        return $this->hasMany(EmployeeJobDetail::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Get the job level that owns the job title.
     */
    public function jobLevel(): BelongsTo
    {
        return $this->belongsTo(JobLevel::class, 'job_level_id', 'job_level_id');
    }

    /**
     * Get the job family that owns the job title.
     */
    public function jobFamily(): BelongsTo
    {
        return $this->belongsTo(JobFamily::class, 'job_family_id', 'job_family_id');
    }

    /**
     * Get the vacancy associated with the job title.
     */
    public function vacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Get the skills required / associated with the job title.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(JobSkillKeyword::class, 'job_title_id', 'job_title_id');
    }
    
    /**
     * Get the educations required / associated with the job title.
     */
    public function educations(): HasMany
    {
        return $this->hasMany(JobEducationKeyword::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Get the experiences required / associated with the job title.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(JobExperienceKeyword::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Override default values for more controlled logging.
     */
    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->useLogName(ActivityLogName::CONFIGURATION->value)
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);

                return match ($eventName) {
                    'created' => __($causerFirstName.' created a new job title record.'),
                    'updated' => __($causerFirstName.' updated a job title information.'),
                    'deleted' => __($causerFirstName.' deleted a job title record.'),
                };
            });
    }
}
