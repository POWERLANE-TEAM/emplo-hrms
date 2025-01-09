<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'emp_status_id';

    protected $fillable = [
        'emp_status_name',
        'emp_status_desc',
    ];
}
