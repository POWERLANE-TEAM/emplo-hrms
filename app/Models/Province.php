<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [];

    /**
     * Get the cities associated with the province.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'province_code', 'province_code');
    }

    /**
     * Get the barangays associated with the province.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barangays(): HasMany
    {
        return $this->hasMany(Barangay::class, 'province_code', 'province_code');
    } 

    /**
     * Get the region of the province.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_code', 'region_code');
    }

}
