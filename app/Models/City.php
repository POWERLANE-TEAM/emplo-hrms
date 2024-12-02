<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $table = 'cities';

    public $timestamps = false;

    protected $fillable = [];

    /**
     * Get the barangays associated with the city.
     */
    public function barangays(): HasMany
    {
        return $this->hasMany(Barangay::class, 'city_code', 'city_code');
    }

    /**
     * Get the province of the city.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    /**
     * Get the region of the city.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_code', 'region_code');
    }
}
