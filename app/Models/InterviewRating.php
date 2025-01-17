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

    /* TODO make getter and setter for description */

    protected static function boot(): void
    {
        parent::boot();

        // ewan basta baka need to
        static::saved(function ($rating) {
            cache()->forget('interviewRatingOptions');
        });
    }

    public static function getRatingValues()
    {
        $ratings = self::all();
        $formattedRatings = [];

        foreach ($ratings as $rating) {
            $formattedRatings[$rating->rating_id] = $rating->rating_id;
        }

        return $formattedRatings;
    }

    public static function getRatings()
    {
        $ratings = self::all();
        $formattedRatings = [];

        foreach ($ratings as $rating) {
            $formattedRatings[$rating->rating_id] = $rating->rating_desc;
        }

        return $formattedRatings;
    }

    /**
     * The final interview ratings that belong to the interview rating code.
     */
    public function finalRatings(): BelongsToMany
    {
        return $this->belongsToMany(FinalInterviewRating::class, 'final_interview_ratings', 'rating_id', 'final_interview_id')
            ->withPivot('parameter_id')
            ->withTimestamps();
    }
}
