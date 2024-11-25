<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class JobLevel extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_level_id';

    public $timestamps = false;

    protected $fillable = [
        'job_level',
        'job_level_name',
        'job_level_desc',
    ];

    /**
     * Get the employees associated with the job level through **EmployeeJobDetail** model.
     */
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, EmployeeJobDetail::class, 'job_level_id', 'employee_id', 'job_level_id', 'employee_id');
    }

    /**
     * Get the job titles associated with the job level.
     */
    public function jobTitles(): HasMany
    {
        return $this->hasMany(JobTitle::class, 'job_level_id', 'job_level_id');
    }
}
