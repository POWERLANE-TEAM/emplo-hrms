<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Process extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'process_id';

    protected $guarded = [
        'process_id',
    ];

    /**
     * Accessor for initial approval date (formatted).
     */
    protected function initialApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Accessor for secondary / final approval date (formatted).
     */
    protected function secondaryApproverSignedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

    /**
     * Accessor for request denied date (formatted).
     */
    protected function deniedAt(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Carbon::make($value)?->format('F d, Y g:i A') ?? null,
        );
    }

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
     * Get the secondary approver who denied the process(e.g.: overtime, leave)
     */
    public function deniedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'denier', 'employee_id');
    }

    /**
     * Get the parent model (Overtime or Leave) that the process belongs to.
     */
    public function processable(): MorphTo
    {
        return $this->morphTo();
    }
}
