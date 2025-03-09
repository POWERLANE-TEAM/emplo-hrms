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

    protected $attributes = [
        'reg_hrs' => 0,
        'reg_nd' => 0,
        'reg_ot' => 0,
        'reg_ot_nd' => 0,
        'rest_hrs' => 0,
        'rest_nd' => 0,
        'rest_ot' => 0,
        'rest_ot_nd' => 0,
        'reg_hol_hrs' => 0,
        'reg_hol_nd' => 0,
        'reg_hol_ot' => 0,
        'reg_hol_ot_nd' => 0,
        'reg_hol_rest_hrs' => 0,
        'reg_hol_rest_nd' => 0,
        'reg_hol_rest_ot' => 0,
        'reg_hol_rest_ot_nd' => 0,
        'spe_hol_hrs' => 0,
        'spe_hol_nd' => 0,
        'spe_hol_ot' => 0,
        'spe_hol_ot_nd' => 0,
        'spe_hol_rest_hrs' => 0,
        'spe_hol_rest_nd' => 0,
        'spe_hol_rest_ot' => 0,
        'spe_hol_rest_ot_nd' => 0,
        'abs_days' => 0,
        'ut_hours' => 0,
        'td_hours' => 0,
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
