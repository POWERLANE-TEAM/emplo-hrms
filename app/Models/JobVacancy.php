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
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class JobVacancy extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'job_vacancy_id';

    protected $guarded = [
        'job_vacancy_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the job title that owns the job vacancy.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Get the job applications associated with the job vacancy.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    /**
     * Override default values for more controlled logging.
     * 
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->useLogName(ActivityLogName::RECRUITMENT->value)
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);
                return match ($eventName) {
                    'created' => __($causerFirstName .' created a new job listing.'),
                    'updated' => __($causerFirstName .' updated a job listing\'s information.'),
                    'deleted' => __($causerFirstName .' deleted a job listing.'),
                };
            });
    }
}
