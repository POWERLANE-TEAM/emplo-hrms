<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyDoc extends Model
{
    use HasFactory;

    protected $primaryKey = 'document_control_id';

    protected $fillable = [
        'document_name',
        'document_desc',
    ];
}
