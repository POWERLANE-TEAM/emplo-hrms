<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Overtime extends Model
{
    use HasFactory;

    protected $primaryKey = 'overtime_id';

    protected $guarded = [
        'overtime_id',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'employee_id');
    }

    public function processes(): MorphMany
    {
        return $this->morphMany(Process::class, 'processable');
    }
}
