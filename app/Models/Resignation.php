<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resignation extends Model
{
    use HasFactory;

    // Need activity logs

    protected $primaryKey = 'resignation_id';

    public $timestamps = false;

    protected $guarded = [
        'resignation_id',
    ];


    // Need modify relationship if applicable

    public function resignationLetter()
    {
        return $this->belongsTo(EmployeeDoc::class, 'emp_resignation_doc_id', 'emp_doc_id');
    }

    public function initialApprover()
    {
        return $this->belongsTo(Employee::class, 'initial_approver', 'employee_id');
    }

    public function resignationStatus()
    {
        return $this->belongsTo(ResignationStatus::class, 'resignation_status_id', 'resignation_status_id');
    }
}