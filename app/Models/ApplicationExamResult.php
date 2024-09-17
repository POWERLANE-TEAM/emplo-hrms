<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationExamResult extends Model
{
    use HasFactory;

    protected $primaryKey = 'exam_result_id';

    protected $guarded = [
        'exam_result_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the result of the examination
    public function applicationExam(): BelongsTo
    {
        return $this->belongsTo(ApplicationExam::class, 'application_exam_id', 'application_exam_id');
    }

    // returns the employee/grader of the exam
    public function grader(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'graded_by', 'employee_id');
    }
}
