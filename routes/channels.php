<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Storage;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user_auth.{userBroadcastId}', function ($user, string $userBroadcastId) {

    $user_session = session()->getId();
    $thisAuthBroadcastId = hash('sha512', $user_session.$user->email.$user_session);

    return $thisAuthBroadcastId == $userBroadcastId;
});

Broadcast::channel('online-users', function (User $user) {
    if (Auth::check()) {
        $userPhoto = $user->photo ?? Storage::url('icons/default-avatar.png');
        $user->load('account');
        $fullName = $user->account->full_name;

        return array_merge(
            $user->only(['user_id', 'email']),
            ['photo' => $userPhoto, 'fullName' => $fullName]
        );
    }

    return false;
});
