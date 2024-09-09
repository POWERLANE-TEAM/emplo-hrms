<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceEvaluationDetails extends Model
{
    use HasFactory;

    protected $primaryKey = 'perf_eval_detail_id';

    protected $guarded = [
        'perf_eval_detail_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function performanceEvaluation(): BelongsTo
    {
        return $this->belongsTo(PerformanceEvaluation::class, 'perf_eval_id', 'perf_eval_id');
    }

    public function performanceCategory(): BelongsTo
    {
        return $this->belongsTo(PerformanceCategory::class, 'performance_id', 'performance_id');
    }
}
