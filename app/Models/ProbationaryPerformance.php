<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProbationaryPerformance extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'probationary_performance_id';

    protected $guarded = [
        'probationary_performance_id',
    ];

    /**
     * Get the performance period whe the performance evaluation occured.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(ProbationaryPerformancePeriod::class, 'period_id', 'period_id');
    }

    /**
     * Get the probationary employee who is being evaluated on the perfomance evaluation.
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
        return $this->hasMany(ProbationaryPerformanceRating::class, 'probationary_performance_id', 'probationary_performance_id');
    }
}
