<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    public function evaluatee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluatee', 'employee_id');
    }

    public function processes(): MorphMany
    {
        return $this->morphMany(Process::class, 'processable');
    }
}
