<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerformanceCategoryRating extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'performance_category_ratings';

    protected $primaryKey = 'perf_cat_rating_id';

    protected $guarded = [
        'perf_cat_rating_id',
    ];

    /**
     * Get the performance detail that owns the entry.
     */
    public function detail(): BelongsTo
    {
        return $this->belongsTo(PerformanceDetail::class, 'perf_detail_id', 'perf_detail_id');
    }
}
