<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDoc extends Model
{
    use HasFactory;

    // pivot table - sort of
    protected $table = 'employee_docs';

    protected $primaryKey = 'emp_doc_id';

    protected $fillable = [
        'emp_doc_id',
        'deleted_at',
    ];
}
