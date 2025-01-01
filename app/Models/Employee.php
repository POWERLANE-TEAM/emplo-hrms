<?php

namespace App\Models;

use App\Enums\Sex;
use App\Enums\CivilStatus;
use Illuminate\Support\Str;
use App\Enums\ActivityLogName;
use Illuminate\Support\Carbon;
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
use App\Http\Helpers\GovernmentMandateContributionsIdFormat;

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
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['last_name'].', '.
                $attributes['first_name'].' '.
                $attributes['middle_name'],
        );
    }

    /**
     * Accessor for contact number
     */
    protected function contactNumber(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => '+63-' . substr($value, 1, 3) . '-' . 
                substr($value, 4, 3) . '-' . 
                substr($value, 7, 4),
        );
    }

    /**
     * Accessor for sex attribute.
     */
    protected function sex(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $sex = Sex::tryFrom($value);
                return $sex ? $sex->label() : ucwords($sex);
            }
        );
    }
    /**
     * Accessor for civil status attribute.
     */
    protected function civilStatus(): Attribute
    {
        return Attribute::make(
            get: function (string $value) {
                $civilStatus = CivilStatus::tryFrom($value);
                return $civilStatus ? $civilStatus->label() : ucwords($civilStatus);
            }
        );
    }

    /**
     * Accessor for SS number.
     */
    protected function sssNo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => GovernmentMandateContributionsIdFormat::formatSsNumber($value),
        );
    }

    /**
     * Accessor for PhilHealth Id.
     */
    protected function philhealthNo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => GovernmentMandateContributionsIdFormat::formatPhilhealthId($value),
        );
    }

    /**
     * Accessor for PagIbig MID.
     */
    protected function pagIbigNo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => GovernmentMandateContributionsIdFormat::formatPagibigMid($value),
        );
    }

    /**
     * Accessor for TIN.
     */
    protected function tinNo(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => GovernmentMandateContributionsIdFormat::formatTin($value),
        );
    }

    /**
     * Accessor for employee's full present address.
     */
    protected function getFullPresentAddressAttribute()
    {
        $barangay = $this->presentBarangay->name;
        $city = $this->presentBarangay->city->name ?? '';
        $province = $this->presentBarangay->province->name ?? '';
        $region = $this->presentBarangay->region->name ?? '';

        return "{$this->present_address}, {$barangay}, {$city}, {$province} {$region}";
    }

    /**
     * Accessor for employee's full permanent address.
     */
    protected function getFullPermanentAddressAttribute()
    {
        $barangay = $this->permanentBarangay->name;
        $city = $this->permanentBarangay->city->name ?? '';
        $province = $this->permanentBarangay->province->name ?? '';
        $region = $this->permanentBarangay->region->name ?? '';

        return "{$this->permanent_address}, {$barangay}, {$city}, {$province} {$region}";
    }

    /**
     * Accessor for shift schedule attribute of start and end time.
     */
    protected function getShiftScheduleAttribute()
    {
        $start = Carbon::make($this->shift->start_time)->format('g:i A');
        $end = Carbon::make($this->shift->end_time)->format('g:i A');

        return "{$start} - {$end}";
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
     * Get the job family / office where employee is the supervisor.
     */
    public function supervisorOf(): HasOne
    {
        return $this->hasOne(JobFamily::class, 'supervisor', 'employee_id');
    }

    /**
     * Get the job family / office where employee is the office_head / manager.
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
    | Overtime Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the overtime requests where employee is the authorizer.
     */
    public function authorizedOvertimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'authorizer', 'employee_id');
    }

    /**
     * Get the overtime requests summaries where employee is the initial approver.
     */
    public function initiallyApprovedOvertimePayrolls(): HasMany
    {
        return $this->hasMany(OvertimePayrollApproval::class, 'initial_approver', 'employee_id');
    }

    /**
     * Get the overtime requests summaries where employee is the secondary approver.
     */
    public function secondaryApprovedOvertimePayrolls(): HasMany
    {
        return $this->hasMany(OvertimePayrollApproval::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get the overtime requests summaries where employee is the third approver.
     */
    public function thirdApprovedOvertimePayrolls(): HasMany
    {
        return $this->hasMany(OvertimePayrollApproval::class, 'third_approver', 'employee_id');
    }

    /**
     * Get the rejected / denied overtime requests by the employee.
     */
    public function deniedOvertimes(): HasMany
    {
        return $this->hasMany(Overtime::class, 'denier', 'employee_id');
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
     * Get the educational attainments of the employee.
     */
    public function educations(): HasMany
    {
        return $this->hasMany(EmployeeEducation::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the work experiences of the employee.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(EmployeeExperience::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the skills of the employee.
     */
    public function skills(): HasMany
    {
        return $this->hasMany(EmployeeSkill::class, 'employee_id', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Leave Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the leave requests where employee is the initial approver.
     */
    public function initiallyApprovedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'initial_approver', 'employee_id');
    }

    /**
     * Get the leave requests where employee is the secondary approver.
     */
    public function secondaryApprovedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get the leave requests where employee is the third approver.
     */
    public function thirdApprovedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'third_approver', 'employee_id');
    }

    /**
     * Get the leave requests where employee is the fourth approver.
     */
    public function fourthApprovedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'fourth_approver', 'employee_id');
    }

    /**
     * Get the leave requests denied by the employee.
     */
    public function deniedLeaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'denier', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Issue Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the issues which statuses are marked by the employee.
     */
    public function markedStatusIssues(): HasMany
    {
        return $this->hasMany(Issue::class, 'status_marker', 'employee_id');
    }

    /**
     * Get the issues reported by the employee.
     */
    public function reportedIssues(): HasMany
    {
        return $this->hasMany(Issue::class, 'issue_reporter', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Incident Records Management
    |--------------------------------------------------------------------------
    */

    /**
     * Get the incidents which are initiated by the employee.
     */
    public function initiatedIncidentReports(): HasMany
    {
        return $this->hasMany(Incident::class, 'initiator', 'employee_id');
    }

    /**
     * Get the incidents reported by the employee.
     */
    public function reportedIncidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'reporter', 'employee_id');
    }

    /**
     * Get the incidents which statuses are marked by the employee.
     */
    public function markedStatusIncidents(): HasMany
    {
        return $this->hasMany(Incident::class, 'status_marker', 'employee_id');
    }

    /**
     * Get incidents where employee is a collaborator
     */
    public function sharedIncidentRecords(): BelongsToMany
    {
        return $this->belongsToMany(Incident::class, 'incident_record_collaborators', 'employee_id', 'incident_id')
            ->using(IncidentRecordCollaborator::class)
            ->withPivot('is_editor');
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
