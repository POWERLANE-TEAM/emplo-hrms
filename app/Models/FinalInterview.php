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

    /**
     * Get the job application that owns the final interview.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    /**
     * Get the employee who is the interviewer of the final interview.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'final_interviewer', 'employee_id');
    }

    /**
     * The interview ratings that belong to the final interview.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function ratings(): BelongsToMany
    {
        return $this->belongsToMany(InterviewRating::class, 'final_interview_ratings', 'final_interview_id', 'rating_id')
            ->withPivot('parameter_id')
            ->withTimestamps();
    }
}
