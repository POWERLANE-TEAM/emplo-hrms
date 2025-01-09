<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Region extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [];

    /**
     * Get the provinces associated with the region.
     */
    public function provinces(): HasMany
    {
        return $this->hasMany(Province::class, 'region_code', 'region_code');
    }

    /**
     * Get the cities associated with the region.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'region_code', 'region_code');
    }

    /**
     * Get the barangays associated with the region.
     */
    public function barangays(): HasMany
    {
        return $this->hasMany(Barangay::class, 'region_code', 'region_code');
    }
}
