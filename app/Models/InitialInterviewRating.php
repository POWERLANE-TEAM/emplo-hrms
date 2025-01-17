<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InitialInterviewRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'init_rating_id';

    protected $guarded = [
        'init_rating_id',
        'created_at',
        'updated_at',
    ];

    public function scopeInterview(Builder $query, $interview): void
    {
        $query->where('init_interview_id', $interview->init_interview_id);
    }

    public function scopeParameter(Builder $query, $parameterId): void
    {
        $query->where('parameter_id', $parameterId);
    }

}
