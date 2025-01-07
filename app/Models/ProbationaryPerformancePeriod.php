<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProbationaryPerformancePeriod extends Model
{
    use HasFactory;

    protected $primaryKey = 'period_id';

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
    ];

    /**
     * Formatted attribute accessor for period interval (e.g: December 01 - December 12, 2025).
     */
    public function getIntervalAttribute()
    {
        $start = Carbon::make($this->start_date)->format('F d');
        $end = Carbon::make($this->end_date)->format('F d, Y');

        return "{$start} - {$end}";
    }


    /**
     * Mutator for performance period name.
     */
    protected function periodName(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => Str::lower($value),
        );
    }

    /**
     * Get probationary employees performance evaluation records during the performance period.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ProbationaryPerformance::class, 'period_id', 'period_id');
    }

    /**
     * Get probationary employee that owns the evaluation period.
     */
    public function probationaryEvaluatee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluatee', 'employee_id');
    }
}
