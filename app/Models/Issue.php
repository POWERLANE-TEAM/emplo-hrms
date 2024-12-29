<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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

    /**
     * Formatted date accessor for filed_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function filedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)->format('F d, Y g:i A'),
        );
    }

    /**
     * Formatted date accessor for occured_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function occuredAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
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
}
