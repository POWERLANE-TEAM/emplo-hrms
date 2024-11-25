<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobFamily extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'job_family_id';

    protected $fillable = [
        'job_family_name',
        'job_family_desc',
        'office_head',
    ];

    /**
     * Get the office head of the job family.
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'office_head', 'employee_id');
    }

    /**
     * Get the employees associated with the job family through **EmployeeJobDetail** model.
     */
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, EmployeeJobDetail::class, 'job_family_id', 'employee_id', 'job_family_id', 'employee_id');
    }

    /**
     * Get the job titles associated with the job family.
     */
    public function jobTitles(): HasMany
    {
        return $this->hasMany(JobTitle::class, 'job_family_id', 'job_family_id');
    }

    /**
     * Get the announcements that belong/include the job family.
     */
    public function announcements(): BelongsToMany
    {
        return $this->belongsToMany(Announcement::class, 'announcement_details', 'job_family_id', 'announcement_id');
    }

    /**
     * Override default values for more controlled logging.
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
                    'created' => __($causerFirstName.' created a new job family record.'),
                    'updated' => __($causerFirstName.' updated a job family information.'),
                    'deleted' => __($causerFirstName.' deleted a job family record.'),
                };
            });
    }
}
