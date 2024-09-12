<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EmployeeComplaint extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_complaint_id';

    protected $guarded = [
        'emp_complaint_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns the complainant of the complaint
    public function complainant(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'complainant', 'employee_id');
    }

    // returns complainee/s of the complaint
    public function complainees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'complaint_complainees', 'emp_complaint_id', 'complainee')
            ->withTimestamps();
    }

    // returns type of the complaint
    public function complaintType(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id', 'complaint_type_id');
    }

    // returns confidentiality preferences of the complaint
    public function confidentiality(): BelongsTo
    {
        return $this->belongsTo(ComplaintConfidentiality::class, 'confidentiality_id' . 'confidentiality_id');
    }

    // returns current status of the complaint
    public function complaintStatus(): BelongsTo
    {
        return $this->belongsTo(ComplaintStatus::class, 'complaint_status_id', 'complaint_status_id');
    }
}
