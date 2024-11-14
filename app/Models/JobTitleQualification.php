<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobTitleQualification extends Model
{
    protected $primaryKey = 'job_title_qual_id';

    protected $fillable = [
        'job_title_id',
        'priority_level',
        'job_title_qual_desc',
    ];

    /**
     * Get the job title that owns the qualification.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jobTitle(): BelongsTo
    {
        return $this->belongsTo(JobTitle::class, 'job_title_id', 'job_title_id');
    }
}
