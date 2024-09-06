<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time'
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'shift_id', 'shift_id');
    }
}
