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

    /**
     * Get the leave name/category of the leave record.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(LeaveCategory::class, 'leave_id', 'leave_id');
    }

    /**
     * Get the employee that owns the leave record.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get all of the leave records' processes.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function processes(): MorphMany
    {
        return $this->morphMany(Process::class, 'processable');
    }
}
