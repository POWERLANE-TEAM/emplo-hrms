<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PerformanceCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'perf_category_id';

    protected $fillable = [
        'perf_category_name',
        'perf_category_desc',
    ];


    public function ratings(): BelongsToMany
    {
        return $this->belongsToMany(PerformanceRating::class, 'performance_category_ratings', 'perf_category_id', 'perf_rating_id')
            ->withPivot('perf_detail_id');
    }
}
