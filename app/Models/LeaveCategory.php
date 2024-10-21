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
        'leave_desc',
    ];

    /**
     * Get the employee leave records associated with the leave category
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'leave_id', 'leave_id');
    }
}
