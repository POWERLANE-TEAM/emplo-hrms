<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProbationaryPerformance extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'probationary_performance_id';

    protected $guarded = [
        'probationary_performance_id',
    ];

    protected $casts = [
        'evaluatee_signed_at' => 'datetime',
        'evaluator_signed_at' => 'datetime',
        'secondary_approver_signed_at' => 'datetime',
        'third_approver_signed_at' => 'datetime',
        'fourth_approver_signed_at' => 'datetime',
    ];

    /**
     * Get the performance period whe the performance evaluation occured.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(ProbationaryPerformancePeriod::class, 'period_id', 'period_id');
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
