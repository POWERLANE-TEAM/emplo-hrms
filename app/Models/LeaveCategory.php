<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveCategory extends Model
{
    use HasFactory;

    protected $primaryKey = 'leave_category_id';

    protected $fillable = [
        'leave_category_name',
        'leave_category_desc',
    ];

    /**
     * Get the employee leave records associated with the leave category
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(EmployeeLeave::class, 'leave_category_id', 'leave_category_id');
    }
}
