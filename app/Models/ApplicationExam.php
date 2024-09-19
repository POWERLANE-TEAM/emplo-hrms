<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationExam extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_exam_id';
    
    protected $guarded = [
        'application_exam_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the name/type of the exam
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'exam_id');
    }

    // returns the application taking the exam
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }

    // returns the result of the exam
    public function result(): HasOne
    {
        return $this->hasOne(ApplicationExamResult::class, 'application_exam_id', 'application_exam_id');
    }
}
