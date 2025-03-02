<?php

namespace App\Models;

use App\Enums\IssueStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Issue extends Model
{
    use HasFactory;

    const CREATED_AT = 'filed_at';

    const UPDATED_AT = 'modified_at';

    protected $primaryKey = 'issue_id';

    protected $guarded = [
        'issue_id',
        'filed_at',
        'modified_at',
    ];

    protected $casts = [
        'occured_at'        => 'datetime',
        'status_marked_at'  => 'datetime',
        'filed_at'          => 'datetime',
        'modified_at'       => 'datetime',
    ];

    /**
     * Local scope builder to get dynamic issue statuses.
     */
    public function scopeOfStatus(Builder $query, array $statuses): void
    {   
        $statuses = collect($statuses);

        $statuses = $statuses->map(
            fn ($status) => $status instanceof IssueStatus ? $status->value : $status
        );

        $query->whereIn('status', $statuses);
    }

    /**
     * Get the employee reporter of the issue.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'issue_reporter', 'employee_id');
    }

    /**
     * Get the status marker (employee of authority) of the issue.
     */
    public function statusMarker(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'status_marker', 'employee_id');
    }

    /**
     * Get the types of the issue.
     */
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(IssueType::class, 'issue_tags', 'issue_id', 'issue_type_id');
    }

    /**
     * Get the attachments associated with the issue.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(IssueAttachment::class, 'issue_id', 'issue_id');
    }

    /**
     * Get the incident report associated with the issue.
     */
    public function incident(): HasOne
    {
        return $this->hasOne(Incident::class, 'originator', 'issue_id');
    }
}
