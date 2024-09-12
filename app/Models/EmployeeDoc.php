<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDoc extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_doc_id';

    protected $guarded = [
        'emp_doc_id',
        'deleted_at',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }
}
