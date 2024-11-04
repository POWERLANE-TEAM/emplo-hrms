<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    const CREATED_AT = 'published_at';

    const UPDATED_AT = 'modified_at';

    protected $primaryKey = 'announcement_id';

    protected $fillable = [
        'announcement_title',
        'announcement_description',
        'published_by',
    ];

    /**
     * Get the publisher of the announcement.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'published_by', 'employee_id');
    }

    /**
     * The job families/offices that belong/tagged on the announcement.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function offices(): BelongsToMany
    {
        return $this->belongsToMany(JobFamily::class, 'announcement_details', 'announcement_id', 'job_family_id')
            ->withTimestamps();
    }
}
