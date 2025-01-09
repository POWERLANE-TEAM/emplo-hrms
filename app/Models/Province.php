<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [];

    /**
     * Get the cities associated with the province.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'province_code', 'province_code');
    }

    /**
     * Get the barangays associated with the province.
     */
    public function barangays(): HasMany
    {
        return $this->hasMany(Barangay::class, 'province_code', 'province_code');
    }

    /**
     * Get the region of the province.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_code', 'region_code');
    }
}
