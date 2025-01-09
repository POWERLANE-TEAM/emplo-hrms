<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalInterviewRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'final_rating_id';

    protected $guarded = [
        'final_rating_id',
        'created_at',
        'updated_at',
    ];
}
