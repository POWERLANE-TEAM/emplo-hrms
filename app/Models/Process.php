<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Process extends Model
{
    use HasFactory;

    protected $primaryKey = 'process_id';

    protected $guarded = [
        'process_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    |
    | Processes: Overtime requests, performance evaluations, employee leaves
    | 
    */

    // returns supervisor who approved/signed the process
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor', 'employee_id');
    }

    // returns area manager who approved/signed the process
    public function areaManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'area_manager', 'employee_id');
    }

    // returns hr manager who approved/signed the process
    public function hrManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hr_manager', 'employee_id');
    }

    // returns which type of process
    public function processable(): MorphTo
    {
        return $this->morphTo();
    }
}
