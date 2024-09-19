<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EmployeeLeave extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_leave_id';

    protected $guarded = [
        'emp_leave_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the employee's leave category/type
    public function leaveCategory(): BelongsTo
    {
        return $this->belongsTo(LeaveCategory::class, 'leave_id', 'leave_id');
    }

    // returns employee who submitted the leave
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function processes(): MorphMany
    {
        return $this->morphMany(Process::class, 'processable');
    }
}
