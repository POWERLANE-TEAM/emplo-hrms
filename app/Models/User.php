<?php

namespace App\Models;

use App\Enums\ActivityLogName;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use CausesActivity;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use LogsActivity;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    protected $primaryKey = 'user_id';

    protected $guard_name = 'web';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'user_id',
        'created_at',
        'updated_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'google_id',
        'facebook_id',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the parent model (Guest, Applicant, or Employee) that the account belongs to.
     */
    public function account(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user status of the user.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(UserStatus::class, 'user_status_id', 'user_status_id');
    }

    /**
     * Override default values for more controlled logging.
     */
    public function getActivityLogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logUnguarded()
            ->useLogName(ActivityLogName::USER_MANAGEMENT->value)
            ->dontSubmitEmptyLogs()
            ->logExcept([
                'password',
                'google_id',
                'facebook_id',
                'remember_token',
                'two_factor_secret',
                'two_factory_recovery_codes',
                'two_factor_confimed_at',
                'deleted_at',
            ])
            ->setDescriptionForEvent(function (string $eventName) {
                $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);

                return match ($eventName) {
                    'created' => __($causerFirstName.' created a new user.'),
                    'updated' => __($causerFirstName.' updated a user information.'),
                    'deleted' => $this->deleted_at
                                ? __($causerFirstName.' temporarily removed a user.')
                                : __($causerFirstName.' permanently deleted a user.'),
                };
            });
    }
}
