<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLifecycle extends Model
{
    use HasFactory;

    protected $primaryKey = 'employee_lifecycle_id';

    protected $guarded = [
        'employee_lifecycle_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'started_at' =>     'datetime',
        'separated_at' =>   'datetime',
        'created_at' =>     'datetime',
        'updated_at' =>     'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
