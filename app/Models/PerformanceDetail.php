<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceDetail extends Model
{
    use HasFactory;

    protected $primaryKey = 'perf_detail_id';

    protected $guarded = [
        'perf_detail_id',
    ];

    /**
     * Get the performance period whe the performance evaluation occured.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(PerformancePeriod::class, 'perf_period_id', 'perf_period_id');
    }

    /**
     * Get the employee who is being evaluated on the perfomance evaluation.
     */
    public function evaluatee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluatee', 'employee_id');
    }

    /**
     * Get the evaluator who approved/signed the performance evaluation.
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluator', 'employee_id');
    }

    /**
     * Get the initial approver who approved/signed the performance evaluation.
     */
    public function initialApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'initial_approver', 'employee_id');
    }

    /**
     * Get the secondary approver who approved/signed the performance evaluation.
     */
    public function secondaryApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get the scored/rated categories of the performance evaluation.
     */
    public function categoryRatings(): HasMany
    {
        return $this->hasMany(PerformanceCategoryRating::class, 'perf_detail_id', 'perf_detail_id');
    }
}
