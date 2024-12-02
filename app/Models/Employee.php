<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'employee_id';

    protected $guarded = [
        'employee_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the employee's full name.
     *
     * @return string
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['last_name'].', '.
                $attributes['first_name'].' '.
                $attributes['middle_name'],
        );
    }

    /**
     * Get the account associated with the employee.
     */
    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'account');
    }

    /**
     * Get the attendance records associated with the employee.
     */
    public function attendanceLogs(): HasMany
    {
        return $this->hasMany(AttendanceLog::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the announcements where employee is the publisher.
     */
    public function publishedAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'published_by', 'employee_id');
    }

    /**
     * Get the job detail of the employee.
     */
    public function jobDetail(): HasOne
    {
        return $this->hasOne(EmployeeJobDetail::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the employment status of the employee through **EmployeeJobDetail** model.
     */
    public function status(): HasOneThrough
    {
        return $this->hasOneThrough(EmploymentStatus::class, EmployeeJobDetail::class, 'employee_id', 'emp_status_id', 'employee_id', 'emp_status_id');
    }

    /**
     * Get the job application associated with the employee through **EmployeeJobDetail** model.
     */
    public function application(): HasOneThrough
    {
        return $this->hasOneThrough(Application::class, EmployeeJobDetail::class, 'employee_id', 'application_id', 'employee_id', 'application_id');
    }

    /**
     * Get the job title of the employee through **EmployeeJobDetail** model.
     */
    public function jobTitle(): HasOneThrough
    {
        return $this->hasOneThrough(JobTitle::class, EmployeeJobDetail::class, 'employee_id', 'job_title_id', 'employee_id', 'job_title_id');
    }

    /**
     * Get the specific area destination of the employee.
     */
    public function specificArea(): HasOneThrough
    {
        return $this->hasOneThrough(SpecificArea::class, EmployeeJobDetail::class, 'employee_id', 'area_id', 'employee_id', 'area_id');
    }

    /**
     * Get the shift schedule of the employee.
     */
    public function shift(): HasOneThrough
    {
        return $this->hasOneThrough(Shift::class, EmployeeJobDetail::class, 'employee_id', 'shift_id', 'employee_id', 'shift_id');
    }

    /**
     * Get the area name where employee is the Area Manager.
     */
    public function areaManagerOf(): HasOne
    {
        return $this->hasOne(SpecificArea::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the office name where employee is the office head.
     */
    public function headOf(): HasOne
    {
        return $this->hasOne(JobFamily::class, 'office_head', 'employee_id');
    }

    /**
     * Get the documents associated with the employee.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDoc::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the overtime records associated with the employee
     */
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the leave records associated with the employee.
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the permanent barangay of the employee.
     */
    public function permanentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'permanent_barangay');
    }

    /**
     * Get the present barangay of the employee.
     */
    public function presentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'present_barangay');
    }

    /*
    |--------------------------------------------------------------------------
    | Recruitment Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the initial interviews where employee is the initial interviewer.
     */
    public function asInitInterviewer(): HasMany
    {
        return $this->hasMany(InitialInterview::class, 'init_interviewer', 'employee_id');
    }

    /**
     * Get the final interviews where employee is the final interviewer.
     */
    public function asFinalInterviewer(): HasMany
    {
        return $this->hasMany(FinalInterview::class, 'final_interviewer', 'employee_id');
    }

    /**
     * Get the application documents where employee is the evaluator.
     */
    public function asApplicationDocsEvaluator(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'evaluated_by', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Training Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the training records associated with the employee.
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'trainee', 'employee_id');
    }

    /**
     * Get the training records where employee is the trainer.
     */
    public function trainingsAsTrainer(): MorphMany
    {
        return $this->morphMany(Training::class, 'trainer');
    }

    /**
     * Get the training comments where employee is the trainer.
     */
    public function commentsAsTrainer(): MorphMany
    {
        return $this->morphMany(Training::class, 'comment');
    }

    /**
     * Get the prepared training records where employee is an HR Personnel.
     */
    public function preparedTrainings(): HasMany
    {
        return $this->hasMany(Training::class, 'prepared_by', 'employee_id');
    }

    /**
     * Get the reviewed/approved training records where employee is the HR Manager.
     */
    public function reviewedTrainings(): HasMany
    {
        return $this->hasMany(Training::class, 'reviewed_by', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Complaint Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the complaint records where employee is the complainant.
     */
    public function complaintsAsComplainant(): HasMany
    {
        return $this->hasMany(EmployeeComplaint::class, 'complainant', 'employee_id');
    }

    /**
     * The complaint records that belong to the complainee(employee).
     */
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

    /**
     * Get the processes(e.g., overtimes, leaves) where employee is the supervisor.
     */
    public function supervisedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'supervisor', 'employee_id');
    }

    /**
     * Get the processes(e.g., overtimes, leaves) where employee is the Area Manager.
     */
    public function areaManagedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the processes(e.g., overtimes, leaves) where employee is the HR Manager.
     */
    public function hrManagedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'hr_manager', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Performance Evaluation Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the performance evaluation records associated with the employee.
     */
    public function performances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'evaluatee', 'employee_id');
    }

    /**
     * Get the performance evalation records where employee is the evaluator.
     */
    public function evaluatedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'evaluator', 'employee_id');
    }

    /**
     * Get the performance evaluation records where employee is the initial approver.
     */
    public function initiallyApprovedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'initial_approver', 'employee_id');
    }

    /**
     * Get the performance evaluation records where employee is the secondary approver.
     */
    public function secondaryApprovedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'secondary_approver', 'employee_id');
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
                    'created' => __($causerFirstName.' created a new employee record.'),
                    'updated' => __($causerFirstName.' updated an employee\'s information.'),
                    'deleted' => __($causerFirstName.' deleted an employee.'),
                };
            });
    }
}
