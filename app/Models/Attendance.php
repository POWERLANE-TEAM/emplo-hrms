<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';

    protected $guarded = [
        'attendance_id',
    ];

    public function employees(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
