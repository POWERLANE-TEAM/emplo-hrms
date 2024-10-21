<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApplicationStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_status_id';

    protected $fillable = [
        'application_status_name',
        'application_status_desc',
    ];

    /**
     * Get the job applications associated with the status.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'application_status_id', 'application_status_id');
    }
}
