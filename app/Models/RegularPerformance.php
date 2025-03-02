<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Cache;

class RegularPerformance extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'regular_performance_id';

    protected $guarded = [
        'regular_performance_id',
    ];

    public function getFinalRatingAttribute()
    {
        $key = config('cache.keys.performance.ratings');

        $cachedValue = Cache::rememberForever($key, fn () => PerformanceRating::all());

        if (! $cachedValue) {
            $this->loadMissing([
                'categoryRatings.rating',
            ]);
        }

        $total = $this->categoryRatings->average(function ($item) {
            return $item->rating->perf_rating;
        });

        $count = $this->categoryRatings->count();
        $ratingAvg = $count > 0 ? number_format($total, 2, '.', '') : 0;
        $roundedValue = round($ratingAvg);
        $intValue = (int) $roundedValue;

        $performanceScale = $cachedValue->firstWhere('perf_rating', $intValue)->perf_rating_name;

        return compact('ratingAvg', 'performanceScale');
    }

    /**
     * Get the performance period whe the performance evaluation occured.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(RegularPerformancePeriod::class, 'period_id', 'period_id');
    }

    /**
     * Get the regular employee who is being evaluated on the perfomance evaluation.
     */
    public function employeeEvaluatee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluatee', 'employee_id');
    }

    /**
     * Get the evaluator who approved/signed the performance evaluation.
     */
    public function employeeEvaluator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluator', 'employee_id');
    }

    /**
     * Get the secondary approver who approved/signed the performance evaluation.
     */
    public function secondaryApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get the third approver who approved/signed the performance evaluation.
     */
    public function thirdApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'third_approver', 'employee_id');
    }

    /**
     * Get the third approver who approved/signed the performance evaluation.
     */
    public function fourthApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'fourth_approver', 'employee_id');
    }

    /**
     * Get the scored/rated categories of the performance evaluation.
     */
    public function categoryRatings(): HasMany
    {
        return $this->hasMany(RegularPerformanceRating::class, 'regular_performance_id', 'regular_performance_id');
    }

    /**
     * Get the performance improvement plan associated with the performance.
     */
    public function pip(): HasOne
    {
        return $this->hasOne(PipPlan::class, 'regular_performance_id', 'regular_performance_id');
    }
}
