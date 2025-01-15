<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentAttachment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'attachment_id';

    protected $guarded = [
        'attachment_id',
    ];

    /**
     * Get the incident that owns the attachment
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class, 'incident_id', 'incident_id');
    }
}
