<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoeRequest extends Model
{
    use HasFactory;

    protected $guarded = [
        'coe_request_id',
        'created_at',
        'updated_at'
    ];


    protected $primaryKey = 'coe_request_id';

    public function requestedBy()
    {
        return $this->belongsTo(Employee::class, 'requested_by', 'employee_id');
    }

    public function generatedBy()
    {
        return $this->belongsTo(Employee::class, 'generated_by', 'employee_id');
    }

    public function empCoeDoc()
    {
        return $this->belongsTo(EmployeeDoc::class, 'emp_coe_doc_id', 'emp_doc_id');
    }
}
