<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InterviewRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'rating_id';

    protected $fillable = [
        'rating_code',
        'rating_desc',
    ];

    /**
     * The final interview ratings that belong to the interview rating code.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function finalRatings(): BelongsToMany
    {
        return $this->belongsToMany(FinalInterviewRating::class, 'final_interview_ratings', 'rating_id', 'final_interview_id')
            ->withPivot('parameter_id')
            ->withTimestamps();
    }
}
