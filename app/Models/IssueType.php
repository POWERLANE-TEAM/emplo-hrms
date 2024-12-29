<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssueType extends Model
{
    use HasFactory;

    protected $primaryKey = 'issue_type_id';

    protected $fillable = [
        'issue_type_name',
    ];

    /**
     * Formatted accessor / mutator for issue_type_name attribute.
     */
    protected function issueTypeName(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
            set: fn (string $value) => strtolower($value),
        );
    }

    /**
     * The issues associated with the type.
     */
    public function issues(): BelongsToMany
    {
        return $this->belongsToMany(Issue::class, 'issue_tags', 'issue_type_id', 'issue_id');
    }
}
