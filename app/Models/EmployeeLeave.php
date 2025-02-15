<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeLeave extends Model
{
    use HasFactory;

    const CREATED_AT = 'filed_at';

    const UPDATED_AT = 'modified_at';

    protected $primaryKey = 'emp_leave_id';

    protected $guarded = [
        'emp_leave_id',
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
     * Formatted date accessor for start_date attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function startDate(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)->format('F d, Y'),
        );
    }

    /**
     * Formatted date accessor for end_date attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function endDate(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)->format('F d, Y'),
        );
    }

    /**
     * Formatted date accessor for initial_approver_signed_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function initialApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Formatted date accessor for secondary_approver_signed_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function secondaryApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Formatted date accessor for third_approver_signed_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function thirdApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Formatted date accessor for fourth_approver_signed_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function fourthApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Formatted date accessor for denied_at attribute (e.g: December 30, 2024 11:59 PM)
     */
    protected function deniedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Getter for the total days requested of employee leave
     */
    public function getTotalDaysRequestedAttribute()
    {
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        return $startDate->diffInDays($endDate);
    }

    /**
     * Get the leave name/category of the leave record.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LeaveCategory::class, 'leave_category_id', 'leave_category_id');
    }

    /**
     * Get the employee that owns the leave record.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the initial approver of the leave request.
     */
    public function initialApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'initial_approver', 'employee_id');
    }

    /**
     * Get the second approver of the leave request.
     */
    public function secondaryApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get the third approver of the leave request.
     */
    public function thirdApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'third_approver', 'employee_id');
    }

    /**
     * Get the fourth approver of the leave request.
     */
    public function fourthApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'fourth_approver', 'employee_id');
    }

    /**
     * Get the user employee who denied the leave request.
     */
    public function deniedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'denier', 'employee_id');
    }

    /**
     * Get the attachments associated with the leave request.
     */
    public function attachments(): HasMany
    {
        return $this->hasMany(EmployeeLeaveAttachment::class, 'emp_leave_id', 'emp_leave_id');
    }
}
