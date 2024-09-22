<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory;

    protected $primaryKey = 'office_id';

    protected $guarded = [
        'office_id',
        'created_at',
        'updated_at',
    ];

    // returns the employee who's the office head
    public function head(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'office_head', 'employee_id');
    }
}
