<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ProbationaryPerformancePeriod extends Model
{
    use HasFactory;

    protected $primaryKey = 'period_id';

    protected $fillable = [
        'evaluatee',
        'period_name',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime,',
    ];

    /**
     * Formatted attribute accessor for period interval (e.g: December 01 - December 12, 2025).
     */
    public function getIntervalAttribute()
    {
        $start = $this->start_date->format('F d');
        $end = $this->end_date->format('F d, Y');

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
