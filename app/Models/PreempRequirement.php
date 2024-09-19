<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreempRequirement extends Model
{
    use HasFactory;

    protected $primaryKey = 'preemp_req_id';

    protected $fillable = [
        'preemp_req_name',
        'preemp_req_desc',
        'sample_file',
    ];

    public function applicationDocs(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'preemp_req_id', 'preemp_req_id');
    }
}
