<?php

use App\Models\User;
use App\Enums\AccountType;
use Illuminate\Support\Facades\Auth;
use App\Actions\GenerateRandomUserAvatar;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('user-auth.{userBroadcastId}', function ($user, string $userBroadcastId) {

    $userSession = session()->getId();
    $userIdentity = $user->email ?? Auth::id();
    $thisAuthBroadcastId = hash('sha512', $userSession . $userIdentity . $userSession);

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

Broadcast::channel('applicant.applying.{userBroadcastId}', function ($user, string $userBroadcastId) {

    $userSession = session()->getId();
    $userIdentity = $user->email ?? Auth::id();
    $thisAuthBroadcastId = hash('sha512', $userSession . $userIdentity . $userSession);

    return $thisAuthBroadcastId == $userBroadcastId && $user->account_type != AccountType::EMPLOYEE->value;
});
