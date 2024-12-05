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

class PerformanceCategory extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'perf_category_id';

    protected $fillable = [
        'perf_category_name',
        'perf_category_desc',
    ];

    /**
     * Accessor / mutator for category name.
     */
    protected function perfCategoryName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::upper($value),
            set: fn (string $value) => Str::lower($value),
        );
    }

    /**
     * Accessor / mutator for category description.
     */
    protected function perfCategoryDesc(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Str::ucfirst($value),
            set: fn (string $value) => Str::lower($value),
        );
    }

    /**
     * The performance ratings that belong to the performance category.
     */
    public function ratings(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceRating::class, 'performance_category_ratings', 'perf_category_id', 'perf_rating_id')
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
            ->submitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);

                return match ($eventName) {
                    'created' => __($causerFirstName.' created a new performance category.'),
                    'updated' => __($causerFirstName.' updated a performance category\'s information.'),
                    'deleted' => __($causerFirstName.' deleted a performance category.'),
                };
            });
    }
}
