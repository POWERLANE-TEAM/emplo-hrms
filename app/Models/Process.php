<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Process extends Model
{
    use HasFactory;

    protected $primaryKey = 'process_id';

    protected $guarded = [
        'process_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Processes: Overtime requests, Employee leaves
    |--------------------------------------------------------------------------
    */

    /**
     * Get the Supervisor who approved/signed the process(e.g.: overtime, leave)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'supervisor', 'employee_id');
    }

    /**
     * Get the Area Manager who approved/signed the process(e.g.: overtime, leave)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function areaManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'area_manager', 'employee_id');
    }

    /**
     * Get the HR Manager who approved/signed the process(e.g.: overtime, leave)
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hrManager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'hr_manager', 'employee_id');
    }

    /**
     * Get the parent model (Overtime or Leave) that the process belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function processable(): MorphTo
    {
        return $this->morphTo();
    }
}
