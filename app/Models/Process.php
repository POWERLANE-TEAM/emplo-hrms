<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Process extends Model
{
    use HasFactory;

    public $timestamps = false;

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
     * Get the initial approver who approved/signed the process(e.g.: overtime, leave)
     */
    public function initialApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'initial_approver', 'employee_id');
    }

    /**
     * Get the secondary approver who approved/signed the process(e.g.: overtime, leave)
     */
    public function secondaryApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'secondary_approver', 'employee_id');
    }

    /**
     * Get the parent model (Overtime or Leave) that the process belongs to.
     */
    public function processable(): MorphTo
    {
        return $this->morphTo();
    }
}
