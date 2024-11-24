<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function head(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'office_head', 'employee_id');
    }

    /**
     * The job titles that belong to the job family.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobTitles(): BelongsToMany
    {
        return $this->belongsToMany(JobTitle::class, 'job_details', 'job_family_id', 'job_title_id')
            ->withTimestamps();
    }

    /**
     * The job levels that belong to the job family.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function jobLevels(): BelongsToMany
    {
        return $this->belongsToMany(JobLevel::class, 'job_details', 'job_family_id', 'job_level_id')
            ->withTimestamps();
    }

    /**
     * The specific areas that belong to the job family.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specificAreas(): BelongsToMany
    {
        return $this->belongsToMany(SpecificArea::class, 'job_details', 'job_family_id', 'area_id')
            ->withTimestamps();
    }

    /**
     * Get the announcements that belong/include the job family.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function announcements(): BelongsToMany
    {
        return $this->belongsToMany(Announcement::class, 'announcement_details', 'job_family_id', 'announcement_id');
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
                    'created' => __($causerFirstName .' created a new job family record.'),
                    'updated' => __($causerFirstName .' updated a job family information.'),
                    'deleted' => __($causerFirstName .' deleted a job family record.'),
                };
            });
    }
}
