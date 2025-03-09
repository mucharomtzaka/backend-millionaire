<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::routes(['middleware' => ['auth:sanctum']]); //if you use Laravel

Broadcast::channel('private-admin.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('group.{groupId}', function ($user, $groupId) {
    return $user->groups->contains($groupId);
});

Broadcast::channel('answer.{groupId}', function ($user, $groupId) {
    return $user->groups->contains($groupId);
});

Broadcast::channel('login-event-test', function ($user) {
    return auth()->check();
});
