<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    use HasFactory;

    protected $primaryKey = 'payslip_id';

    protected $guarded = [
        'payslip_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the employee that owns the payslip.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the payslip's uploader.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'uploaded_by', 'employee_id');
    }

    /**
     * Get the payroll period of the payslip.
     */
    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class, 'payroll_id', 'payroll_id');
    }
}
