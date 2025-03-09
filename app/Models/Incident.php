<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Incident extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'incident_id';

    protected $guarded = [
        'incident_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Formatted date accessor for created_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value = isset($value) ? Carbon::parse($value) : null,
        );
    }

    /**
     * Formatted date accessor for updated_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value = isset($value) ? Carbon::parse($value) : null,
        );
    }

    /**
     * Get the issue where the incident record originated.
     */
    public function originatedFrom(): BelongsTo
    {
        return $this->belongsTo(Issue::class, 'originator', 'issue_id');
    }

    /**
     * Get the initiator (employee) of the incident.
     */
    public function initiatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'initiator', 'employee_id');
    }

    /**
     * Get the employee reporter of the incident.
     */
    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reporter', 'employee_id');
    }

    /**
     * Get the status marker (employee of authority) of the incident.
     */
    public function statusMarker(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'status_marker', 'employee_id');
    }

    /**
     * Get the types of the incident.
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(IssueType::class, 'incident_tags', 'incident_id', 'issue_type_id');
    }

    /**
     * Get the attachments associated with the incident.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(IncidentAttachment::class, 'incident_id', 'incident_id');
    }

    /**
     * Get the collaborators of the incident record.
     */
    public function collaborators(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'incident_record_collaborators', 'incident_id', 'employee_id')
            ->as('access')
            ->using(IncidentRecordCollaborator::class)
            ->withPivot('is_editor');
    }

    /**
     * Override default values for more controlled logging.
     */
    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->useLogName(ActivityLogName::EMPLOYEE->value)
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);

                return match ($eventName) {
                    'created' => __("{$causerFirstName} created a new incident report."),
                    'updated' => __("{$causerFirstName} updated an incident report information"),
                    'deleted' => __("{$causerFirstName} removed an incident report."),
                };
            });
    }
}
