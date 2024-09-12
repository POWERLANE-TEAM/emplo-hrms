<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_id';

    protected $guarded = [
        'application_id',
        'created_at',
        'updated_at',
    ];

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_id');
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'job_vacancy_id');
    }

    public function applicationStatus(): BelongsTo
    {
        return $this->belongsTo(ApplicationStatus::class, 'application_status_id', 'application_status_id');
    }

    public function initialInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'init_interviewer', 'employee_id');
    }

    public function finalInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'final_interviewer', 'employee_id');
    }
}
