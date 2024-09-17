<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // returns the supervisor that approves the leave
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor', 'employee_id');
    }

    // returns the area manager that approves the leave
    public function areaManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'area_manager', 'employee_id');
    }

    // returns the hr manager that approves the leave
    public function hrManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hr_manager', 'employee_id');
    }
}
