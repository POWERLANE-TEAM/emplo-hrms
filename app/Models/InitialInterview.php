<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InitialInterview extends Model
{
    use HasFactory;

    protected $primaryKey = 'interview_id';

    protected $guarded = [
        'interview_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the job application that owns the initial interview.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    /**
     * Get the employee who is the interviewer of the initial interview.
     */
    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'init_interviewer', 'employee_id');
    }
}
