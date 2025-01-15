<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobExperienceKeyword extends Model
{
    use HasFactory;

    protected $primaryKey = 'keyword_id';

    protected $fillable = [
        'job_title_id',
        'keyword',
        'priority',
    ];

    /**
     * Get the job title that owns the experience keyword.
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }
}
