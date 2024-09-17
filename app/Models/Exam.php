<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory;

    protected $primaryKey = 'exam_id';

    protected $fillable = [
        'exam_name',
        'duration',
        'max_score',
    ];

    public function applicationExams(): HasMany
    {
        return $this->hasMany(ApplicationExam::class, 'exam_id', 'exam_id');
    }
}
