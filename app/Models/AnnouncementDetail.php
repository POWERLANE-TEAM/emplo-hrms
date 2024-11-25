<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnouncementDetail extends Model
{
    protected $primaryKey = 'announcement_detail_id';

    protected $fillable = [
        'announcement_id',
        'job_family_id',
    ];
}
