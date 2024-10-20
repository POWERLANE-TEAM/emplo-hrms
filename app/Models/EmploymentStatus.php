<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmploymentStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_status_id';

    protected $fillable = [
        'emp_status_name',
        'emp_status_desc',
    ];

    /**
     * Get the employees associated with the employment status.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'emp_status_id', 'emp_status_id');
    }
}
