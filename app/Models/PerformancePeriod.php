<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PerformancePeriod extends Model
{
    use HasFactory;

    protected $primaryKey = 'perf_period_id';

    protected $fillable = [
        'perf_period_name',
    ];

    /**
     * Accessor / mutator for performance period name.
     */
    protected function perfPeriodName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            set: fn (string $value) => Str::lower($value),
        );
    }

    /**
     * Get the performance evaluation records during the performance period.
     */
    public function details(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'perf_period_id', 'perf_period_id');
    }
}
