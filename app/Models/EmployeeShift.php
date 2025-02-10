<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeShift extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_shift_id';

    protected $guarded = [
        'employee_shift_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Accessor for shift schedule start - end time.
     */
    protected function getScheduleAttribute(): string
    {
        $start = Carbon::make($this->start_time)->format('g:i A');
        $end = Carbon::make($this->end_time)->format('g:i A');

        return "{$start} - {$end}";
    }

    /**
     * Get the shift type that owns the given start - end time.
     * 
     * @return BelongsTo<Shift, EmployeeShift>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }
}
