<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Resignation extends Model
{
    use HasFactory;

    // Need activity logs

    protected $primaryKey = 'resignation_id';

    public $timestamps = false;

    const CREATED_AT = 'filed_at';

    protected $guarded = [
        'resignation_id',
        'filed_at',
    ];

    protected function finalDecisionAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => $attributes['retracted_at'] ?? $attributes['initial_approver_signed_at'],
        );
    }

    // Need modify relationship if applicable

    public function resignationLetter()
    {
        return $this->belongsTo(EmployeeDoc::class, 'emp_resignation_doc_id', 'emp_doc_id');
    }

    public function resigneeLifecycle(): HasOneThrough
    {
        return $this->hasOneThrough(EmployeeLifecycle::class, EmployeeDoc::class, 'employee_id', 'employee_id', 'employee_id', 'employee_id');
    }

    public function resignee(): HasOneThrough
    {
        return $this->hasOneThrough(Employee::class, EmployeeDoc::class, 'emp_doc_id', 'employee_id', 'emp_resignation_doc_id', 'employee_id');
    }

    public function initialApprover()
    {
        return $this->belongsTo(Employee::class, 'initial_approver', 'employee_id');
    }

    public function resignationStatus()
    {
        return $this->belongsTo(ResignationStatus::class, 'resignation_status_id', 'resignation_status_id');
    }
}
