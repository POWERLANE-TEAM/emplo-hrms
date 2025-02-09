<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
    ];

    /**
     * Local scope query builder to get model of Regular shift name.
     */
    public function scopeRegular(Builder $query): void
    {
        $query->where('shift_name', 'Regular');
    }

    /**
     * Local scope query builder to get model of Night Differential name.
     */
    public function scopeNightDifferential(Builder $query)
    {
        $query->where('shift_name', 'Night Differential');
    }
}
