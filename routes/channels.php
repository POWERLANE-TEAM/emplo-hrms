<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user_auth.{userBroadcastId}', function ($user, string $userBroadcastId) {

    $user_session = session()->getId();
    $thisAuthBroadcastId = hash('sha512', $user_session . $user->email . $user_session);

    return $thisAuthBroadcastId == $userBroadcastId;
});
