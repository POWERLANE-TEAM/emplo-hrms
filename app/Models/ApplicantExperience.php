<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicantExperience extends Model
{
    use HasFactory;

    protected $primaryKey = 'applicant_exp_id';

    protected $fillable = [
        'applicant_id',
        'experience_desc',
    ];

    /**
     * Get the applicant that owns the experience record.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_id');
    }
}
