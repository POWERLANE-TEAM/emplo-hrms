<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * Get the applicant that owns the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_id');
    }

    /**
     * Get the employee that owns the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'application_id', 'employee_id');
    }    

    /**
     * Get the documents associated with the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'application_id', 'application_id');
    }

    /**
     * Get the vacancy of the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    /**
     * Get the current status of the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id', 'application_status_id');
    }

    /**
     * Get the initial interview associated with the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function initialInterview(): HasOne
    {
        return $this->hasOne(InitialInterview::class, 'application_id', 'application_id');
    }

    /**
     * Get the employee who is the initial interviewer of the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function initialInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'init_interviewer', 'employee_id');
    }

    /**
     * Get the final interview associated with the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function finalInterview(): HasOne
    {
        return $this->hasOne(FinalInterview::class, 'application_id', 'application_id');
    }

    /**
     * Get the employee who is the final interviewer of the job application.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function finalInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'final_interviewer', 'employee_id');
    }
}
