<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeLeaveAttachment extends Model
{
    use HasFactory;

    protected $primaryKey = 'attachment_id';

    protected $guarded = [
        'attachment_id',
    ];

    /**
     * Get the requested leave that owns the attachment.
     */
    public function leave(): BelongsTo
    {
        return $this->belongsTo(EmployeeLeave::class, 'emp_leave_id', 'emp_leave_id');
    }
}
