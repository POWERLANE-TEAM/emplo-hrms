<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    protected $casts = [
        'start_time'            => 'datetime',
        'end_time'              => 'datetime',
        'authorizer_signed_at'  => 'datetime',
        'denied_at'             => 'datetime',
    ];

    /**
     * Local query builder scope to get ot requests that are filed exactly or more than a week ago.
     */
    public function scopeArchived(Builder $query): void
    {
        $query->where('filed_at', '<=', now()->subWeek());
    }

    /**
     * Local query builder scope to get ot requests that are filed less than a week ago.
     */
    public function scopeRecent(Builder $query): void
    {
        $query->where('filed_at', '>', now()->subWeek());
    }

    /**
     * Local query builder scope to get ot requests that are authorized.
     */
    public function scopeAuthorized(Builder $query): void
    {
        $query->whereNotNull('authorizer_signed_at');
    }

    /**
     * Local query builder scope to get ot requests that are denied.
     */
    public function scopeDenied(Builder $query): void
    {
        $query->whereNotNull('denied_at');
    }

    /**
     * Getter for computed overtime hours requested.
     */
    public function getHoursRequestedAttribute(): string
    {
        return $this->start_time->diff($this->end_time);
    }

    public function payrollApproval(): BelongsTo
    {
        return $this->belongsTo(OvertimePayrollApproval::class, 'payroll_approval_id', 'payroll_approval_id');
    }

    /**
     * Get the authorizer who approved/signed the overtime request.
     */
    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'authorizer', 'employee_id'); 
    }

    /**
     * Get the user employee who denied the overtime.
     */
    public function deniedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'denier', 'employee_id');
    }

    /**
     * Get the employee that owns the overtime records.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
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
                    'created' => __("{$causerFirstName} submitted an overtime request."),
                    'updated' => __("{$causerFirstName} updated an overtime request's information."),
                    'deleted' => __("{$causerFirstName} deleted an overtime request record."),
                };
            });
    }
}
