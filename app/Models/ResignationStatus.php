<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResignationStatus extends Model
{
    protected $primaryKey = 'resignation_status_id';

    protected $guarded = [
        'resignation_status_id',
    ];

    public $timestamps = false;
}
