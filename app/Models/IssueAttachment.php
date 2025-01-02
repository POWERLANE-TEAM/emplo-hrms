<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueAttachment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'attachment_id';

    protected $guarded = [
        'attachment_id',
    ];

    /**
     * Get the issue that owns the attachment
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class, 'issue_id', 'issue_id');
    }
}
