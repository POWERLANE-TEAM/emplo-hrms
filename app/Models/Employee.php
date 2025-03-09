<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use App\Enums\CivilStatus;
use App\Enums\EmploymentStatus as Status;
use App\Enums\ServiceIncentiveLeave;
use App\Http\Helpers\GovernmentMandateContributionsIdFormat;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
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
            get: fn (mixed $value) => '+63-'.substr($value, 1, 3).'-'.
                substr($value, 4, 3).'-'.
                substr($value, 7, 4),
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

    protected function getActualSilCreditsAttribute()
    {
        $this->loadMissing([
            'jobDetail' => fn ($query) => $query->select([
                'emp_job_detail_id',
                'employee_id',
                'hired_at',
            ]),
        ]);

        $dateHired = $this->jobDetail->hired_at;

        $serviceDuration = now()->diff($dateHired);

        if ($serviceDuration->y < 1) {
            if (in_array($dateHired->month, ServiceIncentiveLeave::Q1->getFirstQuarter())) {
                return ServiceIncentiveLeave::Q1->value;
            }

            if (in_array($dateHired->month, ServiceIncentiveLeave::Q2->getSecondQuarter())) {
                return ServiceIncentiveLeave::Q2->value;
            }

            if (in_array($dateHired->month, ServiceIncentiveLeave::Q3->getThirdQuarter())) {
                return ServiceIncentiveLeave::Q3->value;
            }

            if (in_array($dateHired->month, ServiceIncentiveLeave::Q4->getFourthQuarter())) {
                return ServiceIncentiveLeave::Q4->value;
            }
        }

        if ($serviceDuration->y >= 5) {
            return 16;
        }

        if ($serviceDuration->y >= 3) {
            return 14;
        }

        if ($serviceDuration->y >= 2) {
            return 11;
        }

        if ($serviceDuration->y >= 1) {
            return 9;
        }

        return 0;
    }

    /**
     * Query builder scope to get active employees (Probationary, Regular) only.
     */
    public function scopeActiveEmploymentStatus(Builder $query): void
    {
        $query->whereHas('status',
            fn ($subQuery) => $subQuery->whereIn('emp_status_name', [
                Status::PROBATIONARY->label(),
                Status::REGULAR->label(),
            ])
        );
    }

    /**
     * Query builder scope to get inactive employees (Resigned, Retired, or Terminated) only.
     */
    public function scopeInactiveEmploymentStatus(Builder $query): void
    {
        $query->whereHas('status',
            fn ($subQuery) => $subQuery->whereIn('emp_status_name', [
                Status::TERMINATED->label(),
                Status::RESIGNED->label(),
                Status::RETIRED->label(),
            ])
        );
    }

    /**
     * Dynamic local scope to get employees of any type of employment status.
     */
    public function scopeOfEmploymentStatus(Builder $query, BackedEnum|string $employmentStatus): void
    {
        if ($employmentStatus instanceof Status) {
            $employmentStatus = $employmentStatus->label();
        }

        $query->whereHas('status', fn ($subQuery) => $subQuery->where('emp_status_name', $employmentStatus));
    }

    /**
     * Query builder scope to get employees with regular shift.
     */
    public function scopeRegularShift(Builder $query): void
    {
        $query->whereHas('shift.category',
            fn ($subQuery) => $subQuery->where('shift_name', 'Regular'),
        );
    }

    /**
     * Query builder scope to get employees with night differential shift.
     */
    public function scopeNightDifferentialShift(Builder $query): void
    {
        $query->whereHas('shift.category',
            fn ($subQuery) => $subQuery->where('shift_name', 'Night Differential'),
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
     * Get the payroll summary associated with the employee.
     */
    public function payrollSummary(): HasMany
    {
        return $this->hasMany(PayrollSummary::class, 'employee_id', 'employee_id');
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
        return $this->hasOneThrough(EmployeeShift::class, EmployeeJobDetail::class, 'employee_id', 'employee_shift_id', 'employee_id', 'shift_id');
    }

    /**
     * Get the starting / separation date of the employee.
     */
    public function lifecycle(): HasOne
    {
        return $this->hasOne(EmployeeLifecycle::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the payslips associated with the employee.
     */
    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the payslips uploaded by the employee.
     */
    public function uploadedPayslips(): HasMany
    {
        return $this->hasMany(Payslip::class, 'uploaded_by', 'employee_id');
    }

    /**
     * Get the contracts associated with the employee.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the contracts uploaded by the employee.
     */
    public function uploadedContracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'uploaded_by', 'employee_id');
    }

    /**
     * Get the service incentive leave credit associtaed with the employee.
     */
    public function silCredit(): HasOne
    {
        return $this->hasOne(ServiceIncentiveLeaveCredit::class, 'employee_id', 'employee_id');
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
     * Get the performance evaluation records associated with the regular employee.
     */
    public function performancesAsRegular(): HasMany
    {
        return $this->hasMany(RegularPerformance::class, 'evaluatee', 'employee_id');
    }

    /**
     * Get the performance evaluation records associated with the probationary employee.
     */
    public function performancesAsProbationary(): HasMany
    {
        return $this->hasMany(ProbationaryPerformancePeriod::class, 'evaluatee', 'employee_id');
    }

    /**
     * Get the performance evalation records where employee is the evaluator of regular employees.
     */
    public function evaluatedRegularsPerformances(): HasMany
    {
        return $this->hasMany(RegularPerformance::class, 'evaluator', 'employee_id');
    }

    /**
     * Get the performance evalation records where employee is the evaluator of probationary employees.
     */
    public function evaluatedProbationariesPerformances(): HasMany
    {
        return $this->hasMany(ProbationaryPerformance::class, 'evaluator', 'employee_id');
    }

    /**
     * Get regular employees performance evaluation records where employee is the secondary approver.
     */
    public function secondaryApprovedRegularsPerformances(): HasMany
    {
        return $this->hasMany(RegularPerformance::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get probationary employees performance evaluation records where employee is the secondary approver.
     */
    public function secondaryApprovedProbationariesPerformances(): HasMany
    {
        return $this->hasMany(ProbationaryPerformance::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get regular employees performance evaluation records where employee is the third approver.
     */
    public function thirdApprovedRegularsPerformances(): HasMany
    {
        return $this->hasMany(RegularPerformance::class, 'third_approver', 'employee_id');
    }

    /**
     * Get probationary employees performance evaluation records where employee is the third approver.
     */
    public function thirdApprovedProbationariesPerformances(): HasMany
    {
        return $this->hasMany(ProbationaryPerformance::class, 'third_approver', 'employee_id');
    }

    /**
     * Get regular employees performance evaluation records where employee is the fourth approver.
     */
    public function fourthApprovedRegularsPerformances(): HasMany
    {
        return $this->hasMany(RegularPerformance::class, 'fourth_approver', 'employee_id');
    }

    /**
     * Get probationaries employees performance evaluation records where employee is the fourth approver.
     */
    public function fourthApprovedProbationariesPerformances(): HasMany
    {
        return $this->hasMany(ProbationaryPerformance::class, 'fourth_approver', 'employee_id');
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
            ->as('access')
            ->using(IncidentRecordCollaborator::class)
            ->withPivot('is_editor');
    }

    /**
     * Get all of the resignations for the employee through the employee documents.
     */
    public function resignations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Resignation::class,
            EmployeeDoc::class,
            'employee_id',
            'emp_resignation_doc_id',
            'employee_id',
            'emp_doc_id'
        );
    }

    /**
     * Get certificate of employment (COE) associated with the employee.
     */
    public function coeRequests(): HasMany
    {
        return $this->hasMany(CoeRequest::class, 'requested_by');
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
                    'created' => __("{$causerFirstName} created a new employee record."),
                    'updated' => __("{$causerFirstName} updated an employee\'s information."),
                    'deleted' => __("{$causerFirstName} deleted an employee."),
                };
            });
    }
}
