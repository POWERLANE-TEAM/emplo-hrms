<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PreempRequirement extends Model
{
    use HasFactory;

    protected $primaryKey = 'preemp_req_id';

    protected $fillable = [
        'preemp_req_name',
        'sample_file',
    ];

    /**
     * Get the application documents associated with the pre-employment requirement.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function applicationDocs(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'preemp_req_id', 'preemp_req_id');
    }
}
