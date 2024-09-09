<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'document_id';

    protected $fillable = [
        'document_name',
        'document_desc',
    ];

    public function applicants(): BelongsToMany
    {
        return $this->belongsToMany(Applicant::class, 'applicant_docs', 'applicant_id', 'document_id');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'employee_docs', 'employee_id', 'document_id');
    }
}
