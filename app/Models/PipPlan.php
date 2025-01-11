<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PipPlan extends Model
{
    use HasFactory;

    protected $primaryKey = 'pip_id';

    const CREATED_AT = 'generated_at';

    const UPDATED_AT = 'modified_at';

    protected $guarded = [
        'pip_id',
        'generated_at',
        'modified_at',
    ];

    /**
     * Get the performance evaluation that owns the peformance improvement plan.  
     */
    public function regularPerformance(): BelongsTo
    {
        return $this->belongsTo(RegularPerformance::class, 'regular_performance_id', 'regular_performance_id');
    }
}
