<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegularPerformanceRating extends Pivot
{
    use HasFactory;

    public $table = 'regular_performance_ratings';

    public $timestamps = false;

    public $incrementing = false;

    public function category(): BelongsTo
    {
        return $this->belongsTo(PerformanceCategory::class, 'perf_category_id', 'perf_category_id');
    }

    public function rating(): BelongsTo
    {
        return $this->belongsTo(PerformanceRating::class, 'perf_rating_id', 'perf_rating_id');
    }

    /**
     * Get the performance detail that owns the entry.
     */
    public function detail(): BelongsTo
    {
        return $this->belongsTo(RegularPerformance::class, 'regular_performance_id', 'regular_performance_id');
    }
}
