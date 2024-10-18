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

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the applicant of the application
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_id');
    }

    // returns employee's application records
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'application_id', 'employee_id');
    }    

    // returns submitted documents of the application
    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'application_id', 'application_id');
    }

    // returns the examination of the application
    public function exam(): HasOne
    {
        return $this->hasOne(ApplicationExam::class, 'application_id', 'application_id');
    }

    // returns the vacancy of the application
    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    // returns status of the application
    public function status(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id', 'application_status_id');
    }

    // returns initial interview details of the application
    public function initialInterview(): HasOne
    {
        return $this->hasOne(InitialInterview::class, 'application_id', 'application_id');
    }

    // returns employee/initial interviewer of the application
    public function initialInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'init_interviewer', 'employee_id');
    }

    // returns final interview details of the application
    public function finalInterview(): HasOne
    {
        return $this->hasOne(FinalInterview::class, 'application_id', 'application_id');
    }

    // returns employee/final interviewer of the final application
    public function finalInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'final_interviewer', 'employee_id');
    }
}
