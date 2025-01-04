<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegularPerformancePeriod extends Model
{
    use HasFactory;

    protected $primaryKey = 'period_id';

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
    ];

    public function getIntervalAttribute()
    {
        $start = Carbon::make($this->start_date)->format('F d');
        $end = Carbon::make($this->end_date)->format('F d, Y');

        return "{$start} - {$end}";
    }

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
     * Get regular employees performance evaluation records during the performance period.
     */
    public function details(): HasMany
    {
        return $this->hasMany(RegularPerformance::class, 'period_id', 'period_id');
    }
}
