<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Registers the /broadcasting/auth route
        Broadcast::routes();

        // Include the channel authorization callbacks
        require base_path('routes/channels.php');
    }
}
