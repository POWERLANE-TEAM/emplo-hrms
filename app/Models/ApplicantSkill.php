<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicantSkill extends Model
{
    use HasFactory;

    protected $primaryKey = 'applicant_skill_id';
    
    protected $fillable = [
        'applicant_id',
        'skill',
    ];

    /**
     * Get the applicant that owns the skill record.
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class, 'applicant_id', 'applicant_id');
    }
}
