<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplaintConfidentiality extends Model
{
    use HasFactory;

    protected $primaryKey = 'confidentiality_id';

    protected $fillable = [
        'confidentiality_pref',
        'confidentiality_desc',
    ];

    /**
     * Get the employee complaints associated with the confidentiality preference.
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(EmployeeComplaint::class, 'confidentiality_id', 'confidentiality_id');
    }
}