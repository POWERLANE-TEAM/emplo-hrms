<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'province_code';

    protected $fillable = [];
}
