<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeJobDetail extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'emp_job_detail_id';

    protected $guarded = [
        'emp_job_detail_id',
    ];

    protected $casts = [
        'hired_at' => 'datetime',
    ];

    /**
     * Get the employee that owns the job detail.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the job title that owns the job detail.
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }

    /**
     * Get the specific area that owns the job detail.
     */
    public function specificArea(): BelongsTo
    {
        return $this->belongsTo(SpecificArea::class, 'area_id', 'area_id');
    }

    /**
     * Get the employee shift that owns the job detail.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(EmployeeShift::class, 'shift_id', 'employee_shift_id');
    }

    /**
     * Get the employment status that owns the job detail.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(EmploymentStatus::class, 'emp_status_id', 'emp_status_id');
    }

    /**
     * Get the application that owns the job detail.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }
}
