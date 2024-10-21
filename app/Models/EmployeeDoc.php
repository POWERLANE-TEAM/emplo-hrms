<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDoc extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_doc_id';

    protected $guarded = [
        'emp_doc_id',
        'deleted_at',
    ];

    /**
     * Get the employee that owns the documents.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
