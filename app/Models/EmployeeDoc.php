<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmployeeDoc extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_doc_id';

    public $timestamps = false;

    protected $guarded = [
        'emp_doc_id',
        'deleted_at',
    ];

    /**
     * Get the employee that owns the documents.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }


    public function resignation(): HasOne
    {
        return $this->hasOne(Resignation::class, 'emp_resignation_doc_id', 'emp_doc_id');
    }

}
