<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user_auth.{user_broadcast_id}', function ($user, string $user_broadcast_id) {

    $user_session = session()->getId();
    $this_auth_broadcast_id = hash('sha512', $user_session . $user->email . $user_session);

    return $this_auth_broadcast_id == $user_broadcast_id;
}, ['guards' => ['web', 'employee', 'admin']]);
