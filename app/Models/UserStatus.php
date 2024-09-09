<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_status_id';

    protected $fillable = [
        'user_status_name',
        'user_status_desc',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_status_id', 'user_status_id');
    }
}
