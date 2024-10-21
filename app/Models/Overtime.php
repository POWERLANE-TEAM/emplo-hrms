<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Overtime extends Model
{
    use HasFactory;

    protected $primaryKey = 'overtime_id';

    protected $guarded = [
        'overtime_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the employee that owns the overtime records.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get all of the overtime records' processes.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function processes(): MorphMany
    {
        return $this->morphMany(Process::class, 'processable');
    }
}
