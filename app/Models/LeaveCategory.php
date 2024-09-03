<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'leave_id';

    protected $guarded = [
        'leave_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_leaves', 'employee_id', 'leave_id');
    }
}
