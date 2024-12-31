<?php

namespace App\Models;

use App\Actions\GenerateRandomUserAvatar;
use App\Enums\ActivityLogName;
use App\Http\Helpers\Agent;
use App\Notifications\Auth\VerifyEmailQueued;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
     * Accessor for photo attribute.
     *
     * If null, generate a random user avatar based on full name.
     */
    protected function photo(): Attribute
    {
        $username = "{$this->account->last_name}, {$this->account->first_name}";

        return Attribute::make(
            get: fn(mixed $value) => $value ?? app(GenerateRandomUserAvatar::class)($username),
        );
    }

    /**
     * Send the email verification notification.
     *
     * Override the default method to queue the email verification notification.
     * Overrides sendEmailVerificationNotification method from MustVerifyEmail.
     *
     * @see https://stackoverflow.com/questions/52644934/how-to-queue-laravel-5-7-email-verification-email-sending/52647112#52647112:~:text=public%20function-,sendEmailVerificationNotification,-()%0A%7B%0A%20%20%20%20%24this%2D%3Enotify(new%20%5CApp
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued);
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
                if (Auth::check()) {
                    $causerFirstName = Str::ucfirst(Auth::user()->account->first_name);
                } else {
                    try {
                        $isGuest = true;

                        $causerFirstName = 'guest_' . session()->getId();

                        $sessionId = request()->session()->getId();
                        $session = DB::connection(config('session.connection'))
                            ->table(config('session.table', 'sessions'))
                            ->where('id', $sessionId)
                            ->first();

                        $guestTrace = tap(new Agent, fn($agent) => $agent->setUserAgent($session->user_agent));

                        $deviceType = 'Unknown';

                        if ($guestTrace->isDesktop())
                            $deviceType = 'Desktop';
                        elseif ($guestTrace->isMobile())
                            $deviceType = 'Mobile';
                        elseif ($guestTrace->isTablet())
                            $deviceType = 'Tablet';
                    } catch (\Throwable $th) {
                        report($th);
                    }
                }

                $eventDescription = match ($eventName) {
                    'created' => __($causerFirstName . ' created a new user.'),
                    'updated' => __($causerFirstName . ' updated a user information.'),
                    'deleted' => $this->deleted_at
                        ? __($causerFirstName . ' temporarily removed a user.')
                        : __($causerFirstName . ' permanently deleted a user.'),
                };

                try {
                    if (isset($isGuest) && $isGuest) {
                        $guestInfo = [
                            'ip_address' => $session->ip_address,
                            'browser' => $guestTrace->browser() ?? "Unknown",
                            'device_type' => $deviceType,
                            'platform' => $guestTrace->platform() ?? "Unknown",
                            'payload' => $session->payload,
                        ];

                        if (app()->environment('production')) {
                            $compressedGuestInfo = base64_encode(gzcompress(json_encode($guestInfo), 9));
                        } else {
                            $compressedGuestInfo = json_encode($guestInfo);
                        }

                        Log::info('Guest Log: ' . $eventDescription . ' Details: ' . $compressedGuestInfo);
                    }
                } catch (\Throwable $th) {
                    report($th);
                }

                return $eventDescription;
            });
    }
}
