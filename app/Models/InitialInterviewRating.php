<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialInterviewRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'ratings_id';

    protected $guarded = [
        'ratings_id',
        'created_at',
        'updated_at',
    ];

    public function scopeInterview(Builder $query, $interview): void
    {
        $query->where('interview_id', $interview->interview_id);
    }

    public function scopeParameter(Builder $query, $parameterId): void
    {
        $query->where('parameter_id', $parameterId);
    }
}
