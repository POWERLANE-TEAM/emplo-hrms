<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class OvertimePayrollApproval extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'payroll_approval_id';

    protected $guarded = [
        'payroll_approval_id',
    ];

    /**
     * Accessor for initial approval date (formatted).
     */
    protected function initialApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Accessor for secondary approval date (formatted).
     */
    protected function secondaryApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Accessor for third approval date (formatted).
     */
    protected function thirdApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'payroll_id');
    }

    /**
     * Get the initial approver who approved/signed the overtime.
     */
    public function initialApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'initial_approver', 'employee_id');
    }

    /**
     * Get the secondary approver who approved/signed the overtime.
     */
    public function secondaryApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get the third approver who approved/signed the overtime.
     */
    public function thirdApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'third_approver', 'employee_id');
    }

    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'payroll_approval_id', 'payroll_approval_id');
    }
}
