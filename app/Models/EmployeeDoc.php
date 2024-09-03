<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeDoc extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_doc_id';

    protected $guarded = [
        'deleted_at',
    ];
}
