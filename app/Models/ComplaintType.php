<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintType extends Model
{
    use HasFactory;

    protected $primaryKey = 'complaint_type_id';

    protected $fillable = [
        'complaint_type_name',
        'complaint_type_name',
    ];

    public function complaints(): HasMany
    {
        return $this->hasMany(EmployeeComplaint::class, 'complaint_type_id', 'complaint_type_id');
    }
}
