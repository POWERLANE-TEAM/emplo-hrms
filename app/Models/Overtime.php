<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Overtime extends Model
{
    use HasFactory;

    protected $primaryKey = 'overtime_id';

    protected $guarded = [
        'overtime_id',
        'init_approved_at',
        'final_approved_at',
        'created_at',
        'updated_at'
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Returns the employee that request for overtime
    |--------------------------------------------------------------------------
    */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'ot_requestor', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Returns the initial approver of the ot request — supervisor/dept head
    |--------------------------------------------------------------------------
    */
    public function initialApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'init_approved_by', 'employee_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Returns the final approver of the ot request — hr manager
    |--------------------------------------------------------------------------
    */
    public function finalApprover(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'final_approved_by', 'employee_id');
    }
}
