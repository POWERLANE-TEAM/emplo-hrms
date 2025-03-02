<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Application extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'application_id';

    protected $guarded = [
        'application_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'hired_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Local query scope to get rejected applications that were past or equal to specific no. of days.
     */
    public function scopeRejectedDuration(Builder $query, int $duration): void
    {
        $query->where('rejected_at', '>=', now()->subDays($duration));
    }

    /**
     * Get the applicant that owns the job application.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_id');
    }

    /**
     * Get the employee associated with the job application through **EmployeeJobDetail** model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): HasOneThrough
    {
        return $this->hasOneThrough(Employee::class, EmployeeJobDetail::class, 'application_id', 'employee_id', 'application_id', 'employee_id');
    }

    /**
     * Get the documents associated with the job application.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'application_id', 'application_id');
    }

    /**
     * Get the vacancy of the job application.
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    /**
     * Get the current status of the job application.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id', 'application_status_id');
    }

    /**
     * Get the examination associated with the job application.
     */
    public function exam(): HasOne
    {
        return $this->hasOne(ApplicationExam::class, 'application_id', 'application_id');
    }

    /**
     * Get the initial interview associated with the job application.
     */
    public function initialInterview(): HasOne
    {
        return $this->hasOne(InitialInterview::class, 'application_id', 'application_id');
    }

    /**
     * Get the employee who is the initial interviewer of the job application.
     */
    public function initialInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'init_interviewer', 'employee_id');
    }

    /**
     * Get the final interview associated with the job application.
     */
    public function finalInterview(): HasOne
    {
        return $this->hasOne(FinalInterview::class, 'application_id', 'application_id');
    }

    /**
     * Get the employee who is the final interviewer of the job application.
     */
    public function finalInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'final_interviewer', 'employee_id');
    }
}
