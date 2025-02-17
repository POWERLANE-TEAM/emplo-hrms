<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
     * The regular employee's performance category parameter that belong to the rating/scale.
     */
    public function regularCategories(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceCategory::class, 'regular_performance_ratings', 'perf_rating_id', 'perf_category_id')
            ->withPivot('regular_performance_id');
    }

    /**
     * The probationary employee's performance category parameter that belong to the rating/scale.
     */
    public function probationaryCategories(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceCategory::class, 'probationary_performances', 'perf_rating_id', 'perf_category_id')
            ->withPivot('probationary_performance_id');
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
