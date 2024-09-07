<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_id';

    protected $guarded = [
        'employee_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model accessors(get) and mutators(set) below
    |--------------------------------------------------------------------------
    */

    protected function firstName(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
            set: fn(string $value) => strtolower($value),
        );
    }

    protected function middleName(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
            set: fn(string $value) => strtolower($value),
        );
    }

    protected function lastName(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
            set: fn(string $value) => strtolower($value),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'employee_id', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Returns the records of employee's overtime requests
    |--------------------------------------------------------------------------
    */
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'ot_requestor', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Returns records of intial approvals to ot requestors.
    |--------------------------------------------------------------------------
    | 
    | Example of these are immediate supervisors/dept heads, they have authority
    | to initially approved their subordinate's overtime requests. This will 
    | also precede the final approval.
    |
    */
    public function initialApprovals(): HasMany
    {
        return $this->hasMany(Overtime::class, 'init_approved_by', 'employee_id');
    }

    /* 
    |--------------------------------------------------------------------------
    | Returns records of final approvals to ot requestors.
    |--------------------------------------------------------------------------
    */
    public function finalApprovals(): HasMany
    {
        return $this->hasMany(Overtime::class, 'final_approved_by', 'employee_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }

    public function leaves(): BelongsToMany
    {
        return $this->belongsToMany(LeaveCategory::class, 'employee_leaves', 'leave_id', 'employee_id');
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'employee_docs', 'document_id', 'employee_id');
    }
}
