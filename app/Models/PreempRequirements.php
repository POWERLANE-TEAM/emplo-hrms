<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreempRequirements extends Model
{
    use HasFactory;

    protected $primaryKey = 'preemp_req_id';

    protected $fillable = [
        'preemp_req_name',
        'preemp_req_desc',
    ];

    public function applicantDocs(): HasMany
    {
        return $this->hasMany(ApplicantDoc::class, 'preemp_req_id', 'preemp_req_id');
    }
}
