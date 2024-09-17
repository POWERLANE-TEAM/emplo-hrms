<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InitialInterview extends Model
{
    use HasFactory;

    protected $primaryKey = 'init_interview_id';

    protected $guarded = [
        'init_interview_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns application for the initial interview
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    // returns the employee/initial interviewer for the initial interview
    public function initialInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'init_interviewer', 'employee_id');
    }
}
