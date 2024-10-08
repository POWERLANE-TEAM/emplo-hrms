<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'leave_id';

    protected $fillable = [
        'leave_name',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'leave_id', 'leave_id');
    }
}
