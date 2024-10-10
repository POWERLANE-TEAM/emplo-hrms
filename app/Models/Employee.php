<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    // returns the office/jobfamily where employee is office head
    public function headOf(): HasOne
    {
        return $this->hasOne(JobFamily::class, 'office_head', 'employee_id');
    }

    // returns the shift schedule of employee
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }

    // returns the documents of employee
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDoc::class, 'employee_id', 'employee_id');
    }

    // returns the records of employee's overtime requests
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'employee_id', 'employee_id');
    }

    // returns the leave records of employee
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id', 'employee_id');
    }

    // returns employee's permanent barangay address
    public function permanentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'permanent_barangay', 'barangay_code');
    }

    // returns employee's present barangay address
    public function presentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'present_barangay', 'barangay_code');
    }

    /*
    |--------------------------------------------------------------------------
    | Recruitment Records Management
    |--------------------------------------------------------------------------
    */

    // returns application records where employee is initial interviewer
    public function asInitInterviewer(): HasMany
    {
        return $this->hasMany(InitialInterview::class, 'init_interviewer', 'employee_id');
    }

    // returns application records where employee is final interviewer
    public function asFinalInterviewer(): HasMany
    {
        return $this->hasMany(FinalInterview::class, 'final_interviewer', 'employee_id');
    }

    // returns pre employment documents where employee is evaluator
    public function asApplicationDocsEvaluator(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'evaluated_by', 'employee_id');
    }

    // returns exam results where employee is grader
    public function asExamGrader(): HasMany
    {
        return $this->hasMany(ApplicationExamResult::class, 'graded_by', 'employee_id');
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
    | Complaints Records Management
    |--------------------------------------------------------------------------
    */

    public function complaintsAsComplainant(): HasMany
    {
        return $this->hasMany(EmployeeComplaint::class, 'complainant', 'employee_id');
    }

    public function complaintsAsComplainee(): BelongsToMany
    {
        return $this->belongsToMany(EmployeeComplaint::class, 'complaint_complainees', 'complainee', 'emp_complaint_id')
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Process management in overtimes and leaves
    |--------------------------------------------------------------------------
    */

    public function supervisedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'supervisor', 'employee_id');
    }

    public function areaManagedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'area_manager', 'employee_id');
    }

    public function hrManagedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'hr_manager', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Performance Evaluation Records Management
    |--------------------------------------------------------------------------
    */

    // returns the employee's performance records
    public function performances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'evaluatee', 'employee_id');
    }

    // returns signed performances where employee is the evaluator
    public function evaluatedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'evaluator', 'employee_id');
    }

    // returns signed peformances where employee is the supervisor
    public function supervisedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'supervisor', 'employee_id');
    }

    // returns signed peformances where employee is the area manager
    public function areaManagedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'area_manager', 'employee_id');
    }

    // returns signed performances where employee is the hr manager
    public function hrManagedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'hr_manager', 'employee_id');
    }
}
