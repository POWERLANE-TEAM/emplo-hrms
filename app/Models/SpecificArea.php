<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SpecificArea extends Model
{
    use HasFactory;

    protected $primaryKey = 'area_id';

    protected $fillable = [
        'area_name',
        'area_manager',
        'area_desc',
    ];

    /**
     * Get the area manager of the specific area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function areaManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the employees associated with the job family through **EmployeeJobDetail** model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function employees(): HasManyThrough
    {
        return $this->hasManyThrough(Employee::class, EmployeeJobDetail::class, 'area_id', 'employee_id', 'area_id', 'employee_id');
    }
}
