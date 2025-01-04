<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Accessor / mutator for performance period name.
     */
    protected function periodName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
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
}
