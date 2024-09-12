<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'application_status_id';

    protected $fillable = [
        'application_status_name',
        'application_status_desc',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'application_status_id', 'application_status_id');
    }
}
