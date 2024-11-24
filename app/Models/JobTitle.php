<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class JobTitle extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'job_title_id';

    protected $fillable = [
        'job_title',
        'job_desc',
        'department_id',
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
     * Get the employees associated with the job title through **EmployeeJobDetail** model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, EmployeeJobDetail::class, 'job_title_id', 'employee_id', 'job_title_id', 'employee_id');
    }

    /**
     * Get the qualifications associated with the job title.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function qualifications(): HasMany
    {
        return $this->hasMany(JobTitleQualification::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Override default values for more controlled logging.
     * 
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
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
