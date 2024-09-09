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

    // returns the account of this employee
    public function account(): HasOne
    {
        return $this->hasOne(User::class, 'employee_id', 'employee_id');
    }

    // returns attendance records of employee
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    // returns the position of employee
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    // returns the branch of employee
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'branch_id');
    }

    // returns the department of employee
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    // returns the shift schedule of employee
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Overtime Management
    |--------------------------------------------------------------------------
    */

    // returns the records of employee's overtime requests
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'employee_id', 'employee_id');
    }

    // returns approved overtimes by supervisor
    public function supervisorApprovedOvertimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'supervisor', 'employee_id');
    }

    // returns approved overtimes by department head
    public function deptHeadApprovedOvertimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'dept_head', 'employee_id');
    }

    // returns approved overtimes by hr manager
    public function hrManagerApprovedOvertimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'hr_manager', 'employee_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Leave Management
    |--------------------------------------------------------------------------
    */

    // returns the leave records of employee
    public function leavesRequested(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id', 'employee_id');
    }

    // returns approved leave records by supervisor
    public function supervisorApprovedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'supervisor', 'employee_id');
    }

    // returns approved leave records by department head
    public function deptHeadApprovedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'dept_head', 'employee_id');
    }

    // returns approved leave records by hr manager
    public function hrManagerApprovedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'hr_manager', 'employee_id');
    }

    // returns the documents of employee
    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'employee_docs', 'document_id', 'employee_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Performance Management
    |--------------------------------------------------------------------------
    */

    // returns the employee's performance records
    public function performances(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'evaluatee', 'employee_id');
    }

    // returns performance records where employee is supervisor
    public function supervisorSignedPerfEval(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'supervisor', 'employee_id');
    }

    // returns performance records where employee is department head
    public function deptHeadSignedPerfEval(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'dept_head', 'employee_id');
    }

    // returns performance records where employee is hr manager
    public function hrManagerSignedPerfEval(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'hr_manager', 'employee_id');
    }

}
