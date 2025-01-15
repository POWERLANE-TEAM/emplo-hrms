<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeExperience extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_exp_id';

    protected $fillable = [
        'employee_id',
        'experience_desc',
    ];

    /**
     * Get the employee that owns the experience record.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
