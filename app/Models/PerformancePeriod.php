<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformancePeriod extends Model
{
    use HasFactory;

    protected $primaryKey = 'perf_period_id';

    protected $fillable = [
        'perf_period_name',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'perf_period_id', 'perf_period_id');
    }
}
