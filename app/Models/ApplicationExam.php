<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApplicationExam extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_exam_id';

    public $timestamps = false;

    protected $guarded = [
        'application_exam_id',
    ];


    /**
     * Get the job application that owns the examination.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id');
    }
}