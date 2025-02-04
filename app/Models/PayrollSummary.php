<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollSummary extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'payroll_summary_id';

    protected $guarded = [
        'payroll_summary_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the payroll that owns the summary.
     * 
     * @return BelongsTo<Payroll, PayrollSummary>
     */
    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'payroll_id');
    }

    /**
     * Get the employee that owns the payroll summary.
     * 
     * @return BelongsTo<Employee, PayrollSummary>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * To-do:
     * 
     * - Log model event
     */
}
