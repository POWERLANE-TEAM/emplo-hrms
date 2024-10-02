<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PerformanceRating extends Model
{
    use HasFactory;

    protected $primaryKey = 'perf_rating_id';

    protected $fillable = [
        'perf_rating_name',
        'perf_rating_desc',
    ];

    
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceCategory::class, 'performance_category_ratings', 'perf_rating_id', 'perf_category_id')
            ->withPivot('perf_detail_id');
    }
}
