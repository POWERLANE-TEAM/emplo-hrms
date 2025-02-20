<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $primaryKey = 'department_id';

    protected $fillable = [
        'department_name',
        'department_function',
    ];

    /**
     * Get the job titles associated with the department.
     */
    public function jobTitles(): HasMany
    {
        return $this->hasMany(JobTitle::class, 'department_id', 'department_id');
    }
}
