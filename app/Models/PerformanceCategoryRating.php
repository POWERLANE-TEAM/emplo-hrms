<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceCategoryRating extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'perf_cat_rating_id';

    protected $guarded = [
        'perf_cat_rating_id',
    ];

    
    //
}
