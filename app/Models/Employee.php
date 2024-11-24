<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

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
            get: fn (mixed $value, array $attributes) => 
                $attributes['last_name'].', '.
                $attributes['first_name'].' '.
                $attributes['middle_name'],
        );
    }

    /**
     * Get the account associated with the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'account');
    }

    /**
     * Get the job application associated with the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function application(): HasOne
    {
        return $this->hasOne(Application::class, 'application_id', 'employee_id');
    }

    /**
     * Get the employment status of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employmentStatus(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_status_id', 'emp_status_id');
    }

    /**
     * Get the attendance records associated with the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the announcements where employee is the publisher.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publishedAnnouncements(): HasMany
    {
        return $this->hasMany(Announcement::class, 'published_by', 'employee_id');
    }

    /**
     * Get the job detail of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobDetail(): BelongsTo
    {
        return $this->belongsTo(JobDetail::class, 'job_detail_id', 'job_detail_id');
    }

    /**
     * Get the job title of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function jobTitle(): HasOneThrough
    {
        return $this->hasOneThrough(JobTitle::class, JobDetail::class, 'job_detail_id', 'job_title_id', 'job_detail_id', 'job_title_id');
    }

    /**
     * Get the job family/office of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function jobFamily(): HasOneThrough
    {
        return $this->hasOneThrough(JobFamily::class, JobDetail::class, 'job_detail_id', 'job_family_id', 'job_detail_id', 'job_family_id');
    }

    /**
     * Get the specific area destination of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function specificArea(): HasOneThrough
    {
        return $this->hasOneThrough(SpecificArea::class, JobDetail::class, 'job_detail_id', 'area_id', 'job_detail_id', 'area_id');    
    }

    /**
     * Get the area name where employee is the Area Manager.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function areaManagerOf(): HasOne
    {
        return $this->hasOne(SpecificArea::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the office name where employee is the office head.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function headOf(): HasOne
    {
        return $this->hasOne(JobFamily::class, 'office_head', 'employee_id');
    }

    /**
     * Get the shift schedule of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class, 'shift_id', 'shift_id');
    }

    /**
     * Get the documents associated with the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(EmployeeDoc::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the overtime records associated with the employee
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function overtimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the leave records associated with the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the permanent barangay of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function permanentBarangay(): BelongsTo
    {
        return $this->belongsTo(Barangay::class, 'permanent_barangay');
    }

    /**
     * Get the present barangay of the employee.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asInitInterviewer(): HasMany
    {
        return $this->hasMany(InitialInterview::class, 'init_interviewer', 'employee_id');
    }

    /**
     * Get the final interviews where employee is the final interviewer.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function asFinalInterviewer(): HasMany
    {
        return $this->hasMany(FinalInterview::class, 'final_interviewer', 'employee_id');
    }

    /**
     * Get the application documents where employee is the evaluator.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class, 'trainee', 'employee_id');
    }

    /**
     * Get the training records where employee is the trainer.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function trainingsAsTrainer(): MorphMany
    {
        return $this->morphMany(Training::class, 'trainer');
    }

    /**
     * Get the training comments where employee is the trainer.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function commentsAsTrainer(): MorphMany
    {
        return $this->morphMany(Training::class, 'comment');
    }

    /**
     * Get the prepared training records where employee is an HR Personnel.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preparedTrainings(): HasMany
    {
        return $this->hasMany(Training::class, 'prepared_by', 'employee_id');
    }

    /**
     * Get the reviewed/approved training records where employee is the HR Manager.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaintsAsComplainant(): HasMany
    {
        return $this->hasMany(EmployeeComplaint::class, 'complainant', 'employee_id');
    }

    /**
     * The complaint records that belong to the complainee(employee).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supervisedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'supervisor', 'employee_id');
    }

    /**
     * Get the processes(e.g., overtimes, leaves) where employee is the Area Manager.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areaManagedProcesses(): HasMany
    {
        return $this->hasMany(Process::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the processes(e.g., overtimes, leaves) where employee is the HR Manager.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
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
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function performances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'evaluatee', 'employee_id');
    }

    /**
     * Get the performance evalation records where employee is the evaluator.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function evaluatedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'evaluator', 'employee_id');
    }

    /**
     * Get the performance evaluation records where employee is the Supervisor.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function supervisedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'supervisor', 'employee_id');
    }

    /**
     * Get the performance evaluation records where employee is the Area Manager.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areaManagedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the performance evaluation records where employee is the HR Manager.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function hrManagedPerformances(): HasMany
    {
        return $this->hasMany(PerformanceDetail::class, 'hr_manager', 'employee_id');
    }

    /**
     * Override default values for more controlled logging.
     * 
     * @return \Spatie\Activitylog\LogOptions
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
