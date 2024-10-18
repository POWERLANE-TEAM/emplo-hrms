<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Guest extends Model
{
    use HasFactory;

    protected $primaryKey = 'guest_id';

    protected $guarded = [
        'guest_id',
        'created_at',
        'updated_at',
    ];

    public function account(): MorphOne
    {
        return $this->morphOne(User::class, 'account');
    }
}
