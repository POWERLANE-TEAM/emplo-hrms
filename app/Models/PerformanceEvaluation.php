<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceEvaluation extends Model
{
    use HasFactory;

    protected $primaryKey = 'perf_eval_id';

    protected $guarded = [
        'perf_eval_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the employee being evaluated
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluatee', 'employee_id');
    }

    // returns the supervisor of the performance evaluation
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor', 'employee_id');
    }

    // returns the department head of the performance evaluation
    public function departmentHead(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'dept_head', 'employee_id');
    }

    // returns the hr manager of the performance evaluation
    public function hrManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hr_manager', 'employee_id');
    }
}
