<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PreempRequirement extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $primaryKey = 'preemp_req_id';

    protected $fillable = [
        'preemp_req_name',
        'sample_file',
    ];

    /**
     * Get the application documents associated with the pre-employment requirement.
     */
    public function applicationDocs(): HasMany
    {
        return $this->hasMany(ApplicationDoc::class, 'preemp_req_id', 'preemp_req_id');
    }

    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->useLogName(ActivityLogName::CONFIGURATION->value)
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);

                return match ($eventName) {
                    'created' => __($causerFirstName.' created a new pre-employment requirement.'),
                    'updated' => __($causerFirstName.' updated a pre-employment requirement\'s information.'),
                    'deleted' => __($causerFirstName.' deleted a pre-employment requirement.'),
                };
            });
    }
}
