<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class IncidentRecordCollaborator extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];
}
