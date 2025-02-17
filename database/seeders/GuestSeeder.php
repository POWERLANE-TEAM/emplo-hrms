<?php

namespace Database\Seeders;

use App\Enums\AccountType;
use App\Enums\UserStatus as EnumUserStatus;
use App\Models\Guest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

function createGuests($chunkStart, $chunk)
{
    activity()->withoutLogs(function () use ($chunkStart, $chunk) {
        try {
            $chunkEnd = $chunkStart + $chunk;

            for ($i = $chunkStart; $i < $chunkEnd; $i++) {
                DB::transaction(function () use ($i) {
                    try {
                        $guest = Guest::factory()->create([
                            'created_at' => fake()->dateTimeBetween('-5 years', 'now'),
                        ]);

                        $users_data = [
                            'account_type' => AccountType::GUEST,
                            'account_id' => $guest->guest_id,
                            'email' => 'guest.'.str_pad($i, 3, '0', STR_PAD_LEFT).'@gmail.com',
                            'password' => Hash::make('UniqP@ssw0rd'),
                            'user_status_id' => EnumUserStatus::ACTIVE,
                            'email_verified_at' => fake()->dateTimeBetween('-10 days', 'now'),
                        ];

                        User::factory()->create($users_data);
                    } catch (\Exception $e) {
                        Log::error('Exception: '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
                    }
                });
            }
        } catch (\Throwable $th) {
            Log::error('Exception: '.$th->getMessage().' in '.$th->getFile().' on line '.$th->getLine());
        }

        return ['result' => true];
    });
}

/**
 * Seeder class for a Applicants account with roles and permissions.
 *
 * @method void run() - Seeds the applicants table with initial data.
 *
 * @param  int|null  $count  The number of seeds to create. Default is 1.
 * @param  int|null  $start  The starting point for seeding. Default is 0.
 * @param  int|null  $concurrencyCount  The number of concurrent processes. Default is 10.
 *                                      - Adjust if 10 concurrent processes is too much for your device.
 * @return void
 */
class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Adjust if 10 concurrent processes are too much for your device.
     *
     * @param  int|null  $count  The number of seeds to create. Default is 1.
     * @param  int|null  $start  The starting point for seeding. Default is 0.
     * @param  int|null  $concurrencyCount  The number of concurrent processes. Default is 10.
     */
    public function run(?int $count = null, ?int $start = null, ?int $concurrencyCount = null): void
    {
        $count = $count ?? env('APP_USER_SEEDING_COUNT', 30);

        $start = $start ?? Guest::max('guest_id') + 1;

        $concurrencyCount = $concurrencyCount ?? env('APP_MAX_CONCURRENT_COUNT', 10);
        $chunkCount = ceil($count / $concurrencyCount);

        Guest::unguard();

        $tasks = [];
        for ($i = 0; $i < $concurrencyCount; $i++) {
            $chunkStart = $start + ($chunkCount * $i);
            $tasks[] = fn () => createGuests($chunkStart, $chunkCount);
        }

        Concurrency::run($tasks);

        Guest::reguard();
    }
}
