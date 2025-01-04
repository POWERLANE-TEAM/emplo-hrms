<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProbationaryPerformanceRating extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    public $incrementing = false;

    /**
     * Get the performance detail that owns the entry.
     */
    public function detail(): BelongsTo
    {
        return $this->belongsTo(ProbationaryPerformance::class, 'probationary_performance_id', 'probationary_performance_id');
    }
}
