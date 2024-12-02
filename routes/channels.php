<?php

use App\Actions\GenerateRandomUserAvatar;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

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
        $fullName = $user->account->full_name;
        $userPhoto = $user->photo ?? app(GenerateRandomUserAvatar::class)($fullName);
        $user->load('account');

        return array_merge(
            $user->only(['user_id', 'email']),
            ['photo' => $userPhoto, 'fullName' => $fullName]
        );
    }

    return false;
});
