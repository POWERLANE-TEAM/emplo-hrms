<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Training extends Model
{
    use HasFactory;

    protected $primaryKey = 'training_id';

    protected $guarded = [
        'training_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns trainer that may be employee or outsourced
    public function trainer(): MorphTo
    {
        return $this->morphTo();
    }

    // returns comment that may belong to employee or outsourced trainer
    public function comment(): MorphTo
    {
        return $this->morphTo();
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'prepared_by', 'employee_id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'reviewed_by', 'employee_id');
    }
}
