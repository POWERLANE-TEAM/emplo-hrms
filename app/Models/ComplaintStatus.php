<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'complaint_status_id';

    protected $fillable = [
        'complaint_status_name',
        'complaint_status_desc',
    ];

    public function employeeComplaints(): HasMany
    {
        return $this->hasMany(EmployeeComplaint::class, 'complaint_status_id', 'complaint_status_id');
    }
}
