<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    use SoftDeletes;
    use LogsActivity;

    const CREATED_AT = 'published_at';

    const UPDATED_AT = 'modified_at';

    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'announcement_title',
        'announcement_description',
        'published_by',
    ];

    /**
     * Get the publisher of the announcement.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'published_by', 'employee_id');
    }

    /**
     * The job families/offices that belong/tagged on the announcement.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function offices(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'announcement_details', 'announcement_id', 'job_family_id');
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
                    'created' => __($causerFirstName.' posted a new announcement.'),
                    'updated' => __($causerFirstName.' updated an announcement'),
                    'deleted' => __($causerFirstName.' deleted an announcement.'),
                };
            });
    }
}
