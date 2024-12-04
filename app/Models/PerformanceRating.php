<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PerformanceRating extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'perf_rating_id';

    protected $fillable = [
        'perf_rating',
        'perf_rating_name',
    ];

    /**
     * Accessor / mutator for performance rating name.
     */
    protected function perfRatingName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            set: fn (string $value) => Str::lower($value),
        );
    }

    /**
     * The performance category parameter that belong to the rating/scale.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceCategory::class, 'performance_category_ratings', 'perf_rating_id', 'perf_category_id')
            ->withPivot('perf_detail_id');
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
                    'created' => __($causerFirstName.' created a new performance rating.'),
                    'updated' => __($causerFirstName.' updated a performance rating\'s information'),
                    'deleted' => __($causerFirstName.' deleted a performance rating'),
                };
            });
    }
}
