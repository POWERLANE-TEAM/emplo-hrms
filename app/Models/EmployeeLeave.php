<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class EmployeeLeave extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'emp_leave_id';

    protected $guarded = [
        'emp_leave_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the leave name/category of the leave record.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LeaveCategory::class, 'leave_id', 'leave_id');
    }

    /**
     * Get the employee that owns the leave record.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get all of the leave records' processes.
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
            ->useLogName(ActivityLogName::LEAVE->value)
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);

                return match ($eventName) {
                    'created' => __($causerFirstName.' submitted a request for leave.'),
                    'updated' => __($causerFirstName.' updated his/her request for leave.'),
                    'deleted' => __($causerFirstName.' deleted his/her request for leave.'),
                };
            });
    }
}
