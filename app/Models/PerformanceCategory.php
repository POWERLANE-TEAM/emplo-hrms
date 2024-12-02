<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

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
