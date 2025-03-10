<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Get the job applications associated with the job vacancy.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    /**
     * Override default values for more controlled logging.
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
                    'created' => __($causerFirstName.' created a new job listing.'),
                    'updated' => __($causerFirstName.' updated a job listing\'s information.'),
                    'deleted' => __($causerFirstName.' deleted a job listing.'),
                };
            });
    }
}
