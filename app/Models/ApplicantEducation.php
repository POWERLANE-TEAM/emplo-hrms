<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantEducation extends Model
{
    use HasFactory;

    protected $primaryKey = 'applicant_education_id';

    protected $fillable = [
        'applicant_id',
        'education',
    ];

    /**
     * Get the applicant that owns the education record.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_id');
    }
}
