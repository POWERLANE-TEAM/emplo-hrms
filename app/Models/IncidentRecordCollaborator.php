<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IncidentRecordCollaborator extends Pivot
{
    use HasFactory;

    public $timestamps = false;

    public $incrementing = false;

    protected $guarded = [];
}
