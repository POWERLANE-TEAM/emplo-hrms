<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceLog extends Model
{
    public $timestamps = false;
    
    public $incrementing = false;

    protected $primaryKey = 'uid';

    protected $fillable = [
        'uid',
        'employee_id',
        'state',
        'type',
        'timestamp',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
