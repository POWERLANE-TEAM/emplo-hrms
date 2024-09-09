<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerformanceCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'performance_id';

    protected $fillable = [
        'performance_name',
        'performance_desc',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function performanceEvaluationDetails(): HasMany
    {
        return $this->hasMany(PerformanceEvaluationDetails::class, 'performance_id', 'performance_id');
    }
}
