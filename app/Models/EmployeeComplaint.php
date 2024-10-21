<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    /**
     * Get the complainant associated with the complaint record.
     */
    public function complainant(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'complainant', 'employee_id');
    }

    /**
     * The complainees that belong to the complaint record.
     */
    public function complainees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'complaint_complainees', 'emp_complaint_id', 'complainee')
            ->withTimestamps();
    }

    /**
     * Get the complaint name/type of the complaint record.
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id', 'complaint_type_id');
    }

    /**
     * Get the confidentiality preference of the complaint record.
     */
    public function confidentiality(): BelongsTo
    {
        return $this->belongsTo(ComplaintConfidentiality::class, 'confidentiality_id'.'confidentiality_id');
    }

    /**
     * Get the current status of the complaint.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ComplaintStatus::class, 'complaint_status_id', 'complaint_status_id');
    }
}
