<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'event',
        'date',
        'type',
    ];

    // date accessor

    /**
     * Override default values for more controlled logging.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName(ActivityLogName::CONFIGURATION->value)
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);
                return match ($eventName) {
                    'created' => __($causerFirstName.' created a new holiday on the calendar'),
                    'updated' => __($causerFirstName.' updated a holiday\'s information on the calendar.'),
                    'deleted' => __($causerFirstName.' deleted a holiday on the calendar'),
                };
            });
    }
}
