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

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns which period the performance evaluation occurred
    public function period(): BelongsTo
    {
        return $this->belongsTo(PerformancePeriod::class, 'perf_period_id', 'perf_period_id');
    }

    // returns the employee being evaluated
    public function evaluatee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluatee', 'employee_id');
    }

    // returns evaluator who approvally signed the performance evaluation
    public function signedEvaluator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluator', 'employee_id');
    }

    // returns supervisor who approvally signed the performance evaluation
    public function signedSupervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor', 'employee_id');
    }

    // returns area manager who approvally signed the performance evaluation
    public function signedAreaManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'area_manager', 'employee_id');
    }

    // returns hr manager who approvally signed the performance evaluation
    public function signedHrManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hr_manager', 'employee_id');
    }

    // returns the scored/rated categories of the performance evalution
    public function categoryRatings(): HasMany
    {
        return $this->hasMany(PerformanceCategoryRating::class, 'perf_detail_id', 'perf_detail_id');
    }
}
