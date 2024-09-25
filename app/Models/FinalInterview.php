<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FinalInterview extends Model
{
    use HasFactory;

    protected $primaryKey = 'final_interview_id';

    protected $guarded = [
        'final_interview_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the application of the final interview
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    // returns the employee/final interviewer of the final interview
    public function finalInterviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'final_interviewer', 'employee_id');
    }

    public function interviewRatings(): BelongsToMany
    {
        return $this->belongsToMany(InterviewRating::class, 'final_interview_ratings', 'final_interview_id', 'rating_id')
            ->withPivot('parameter_id')
            ->withTimestamps();
    }
}
