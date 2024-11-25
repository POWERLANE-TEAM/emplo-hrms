<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'shift_name',
        'start_time',
        'end_time',
    ];
}
