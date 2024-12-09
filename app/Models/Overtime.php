<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;

    const CREATED_AT = 'filed_at';

    const UPDATED_AT = 'modified_at';

    protected $primaryKey = 'overtime_id';

    protected $guarded = [
        'overtime_id',
        'filed_at',
        'modified_at',
    ];

    /**
     * Accessor(only) for start time attribute.
     */
    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('g:i A')
        );
    }
    
    /**
     * Accessor(only) for end time attribute.
     */
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('g:i A')
        );
    }

    /**
     * Accessor(only) for filing date attribute.
     */
    protected function filedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('F d Y')
        );
    }

    /**
     * Getter for computed overtime hours requested.
     */
    public function getHoursRequested()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return $start->diffForHumans($end);
    }

    /**
     * Get the employee that owns the overtime records.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get all of the overtime records' processes.
     */
    public function processes(): MorphMany
    {
        return $this->morphMany(Process::class, 'processable');
    }
}
