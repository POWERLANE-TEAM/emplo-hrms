<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'account');
    }

    // returns employment status of employee
    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_status_id', 'emp_status_id');
    }

    // returns attendance records of employee
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    // returns the job title of employee
    public function jobDetail(): BelongsTo
    {
        return $this->belongsTo(JobDetail::class, 'job_detail_id', 'job_detail_id');
    }

    // returns the specific area where employee is area manager
    public function areaManagerOf(): HasOne
    {
        return $this->hasOne(SpecificArea::class, 'area_manager', 'employee_id');
    }

    // returns the shift schedule of employee
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }

    // returns the documents of employee
    // public function documents(): BelongsToMany
    // {
    //     return $this->belongsToMany(Document::class, 'employee_docs', 'document_id', 'employee_id');
    // }

    /*
    |--------------------------------------------------------------------------
    | Recruitment Records Management
    |--------------------------------------------------------------------------
    */

    // returns application records where employee is initial interviewer
    public function asInitInterviewer(): HasMany
    {
        return $this->hasMany(Application::class, 'init_interviewer', 'employee_id');
    }

    // returns application records where employee is final interviewer
    public function asFinalInterviewer(): HasMany
    {
        return $this->hasMany(Application::class, 'final_interviewer', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Training Records Management
    |--------------------------------------------------------------------------
    */

    // returns training records of employee
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'trainee', 'employee_id');
    }

    // returns training records where employee is trainer
    public function trainingsAsTrainer(): MorphMany
    {
        return $this->morphMany(Training::class, 'trainer');
    }

    // returns training comments where employee is trainer
    public function commentsAsTrainer(): MorphMany
    {
        return $this->morphMany(Training::class, 'comment');
    }

    // returns prepared training records where employee is hr personnel
    public function preparedTrainings(): HasMany
    {
        return $this->hasMany(Training::class, 'prepared_by', 'employee_id');
    }

    // returns reviewed/approved training records where employee is hr manager
    public function reviewedTrainings(): HasMany
    {
        return $this->hasMany(Training::class, 'reviewed_by', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Overtime Records Management
    |--------------------------------------------------------------------------
    */

    // returns the records of employee's overtime requests
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'employee_id', 'employee_id');
    }

    // returns approved overtimes by supervisor
    public function approvedOtAsSupervisor(): HasMany
    {
        return $this->hasMany(Overtime::class, 'supervisor', 'employee_id');
    }

    // returns approved overtimes by department head
    public function approvedOtAsAreaManager(): HasMany
    {
        return $this->hasMany(Overtime::class, 'area_manager', 'employee_id');
    }

    // returns approved overtimes by hr manager
    public function approvedOtAsHrManager(): HasMany
    {
        return $this->hasMany(Overtime::class, 'hr_manager', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Records Management
    |--------------------------------------------------------------------------
    */

    // returns the leave records of employee
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id', 'employee_id');
    }

    // returns approved leave records by supervisor
    public function approvedLeavesAsSupervisor(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'supervisor', 'employee_id');
    }

    // returns approved leave records by department head
    public function approvedLeavesAsAreaManager(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'area_manager', 'employee_id');
    }

    // returns approved leave records by hr manager
    public function approvedLeavesAsHrManager(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'hr_manager', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Performance Evaluation Records Management
    |--------------------------------------------------------------------------
    */

    // returns the employee's performance records
    public function performances(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'evaluatee', 'employee_id');
    }

    // returns signed performance records where employee is supervisor
    public function signedPerfEvalAsSupervisor(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'supervisor', 'employee_id');
    }

    // returns signed performance records where employee is department head
    public function signedPerfEvalAsAreaManager(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'area_manager', 'employee_id');
    }

    // returns signed performance records where employee is hr manager
    public function signedPerfEvalAsHrManager(): HasMany
    {
        return $this->hasMany(PerformanceEvaluation::class, 'hr_manager', 'employee_id');
    }
}
