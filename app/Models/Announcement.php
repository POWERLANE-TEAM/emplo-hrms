<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Announcement extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

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
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'published_by', 'employee_id');
    }

    /**
     * The job families/offices that belong/tagged on the announcement.
     */
    public function offices(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'announcement_details', 'announcement_id', 'job_family_id');
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
                    'created' => __($causerFirstName.' posted a new announcement.'),
                    'updated' => __($causerFirstName.' updated an announcement'),
                    'deleted' => $this->deleted_at
                                ? __($causerFirstName.' temporarily removed an announcement.')
                                : __($causerFirstName.' permanently deleted an announcement.'),
                };
            });
    }
}
