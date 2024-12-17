<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Illuminate\Support\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;
    use LogsActivity;

    const CREATED_AT = 'filed_at';

    const UPDATED_AT = 'modified_at';

    protected $primaryKey = 'overtime_id';

    protected $guarded = [
        'overtime_id',
        'filed_at',
        'modified_at',
    ];

    /**
     * Accessor / mutator for start time attribute.
     */
    protected function startTime(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('g:i A'),
            set: fn (mixed $value) => Carbon::parse($value)->format('H:i:s'),
        );
    }
    
    /**
     * Accessor / mutator for end time attribute.
     */
    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('g:i A'),
            set: fn (mixed $value) => Carbon::parse($value)->format('H:i:s'),
        );
    }

    /**
     * Accessor(only) for filing date attribute.
     */
    protected function filedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::parse($value)->format('F d, Y')
        );
    }

    /**
     * Getter for computed overtime hours requested.
     */
    public function getHoursRequested(): string
    {
        $start = Carbon::createFromFormat('g:i A', $this->start_time);
        $end = Carbon::createFromFormat('g:i A', $this->end_time);
    
        return $start->diff($end)->format('%h hours and %i minutes');
    }
    
    public function date(): Attribute
    {
        return Attribute::make(
            set: fn (mixed $value) => Carbon::parse($value)->format('Y-m-d')
        );
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

    /**
     * Override default values for more controlled logging.
     */
    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->useLogName(ActivityLogName::OVERTIME->value)
            ->dontSubmitEmptyLogs()
            ->submitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);

                return match ($eventName) {
                    'created' => __($causerFirstName.' submitted an overtime request.'),
                    'updated' => __($causerFirstName.' updated an overtime request\'s information.'),
                    'deleted' => __($causerFirstName.' deleted an overtime request record.'),
                };
            });
    }
}
