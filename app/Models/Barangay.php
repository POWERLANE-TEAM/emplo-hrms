<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barangay extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'barangay_code';

    protected $fillable = [];

    /*
    |--------------------------------------------------------------------------
    | Define model relationships below
    |--------------------------------------------------------------------------
    */

    // returns employees who are permanent barangay residents
    public function permanentResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'permanent_barangay', 'barangay_code');
    }

    // returns employees who are present barangay residents
    public function presentResidents(): HasMany
    {
        return $this->hasMany(Employee::class, 'present_barangay', 'barangay_code');
    }
}
