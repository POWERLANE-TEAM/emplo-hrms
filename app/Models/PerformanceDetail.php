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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(PerformancePeriod::class, 'perf_period_id', 'perf_period_id');
    }

    /**
     * Get the employee who is being evaluated on the perfomance evaluation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evaluatee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluatee', 'employee_id');
    }

    /**
     * Get the evaluator who approved/signed the performance evaluation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function signedEvaluator(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluator', 'employee_id');
    }

    /**
     * Get the Supervisor who approved/signed the performance evaluation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function signedSupervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor', 'employee_id');
    }

     /**
     * Get the Area Manager who approved/signed the performance evaluation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function signedAreaManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the HR Manager who approved/signed the performance evaluation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function signedHrManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hr_manager', 'employee_id');
    }

    /**
     * Get the scored/rated categories of the performance evaluation.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categoryRatings(): HasMany
    {
        return $this->hasMany(PerformanceCategoryRating::class, 'perf_detail_id', 'perf_detail_id');
    }
}
