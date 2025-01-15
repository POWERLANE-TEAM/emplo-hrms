<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Contract extends Model
{
    use HasFactory;

    protected $primaryKey = 'contract_id';

    protected $guarded = [
        'contract_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the employee that owns the contract.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    /**
     * Get the contract's uploader.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'uploaded_by', 'employee_id');
    }
}
