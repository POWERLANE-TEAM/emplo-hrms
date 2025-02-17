<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use App\Enums\PhHolidayType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Holiday extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'event',
        'date',
        'type',
    ];

    /**
     * Local query builder scope to get regular holidays.
     */
    public function scopeRegular(Builder $query): void
    {
        $query->where('type', PhHolidayType::REGULAR->value);
    }

    /**
     * Local query builder scope to get special(working/non-working) holidays.
     */
    public function scopeSpecial(Builder $query): void
    {
        $query->where('type', PhHolidayType::SPECIAL_NON_WORKING->value)
            ->orWhere('type', PhHolidayType::SPECIAL_WORKING->value);
    }

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
