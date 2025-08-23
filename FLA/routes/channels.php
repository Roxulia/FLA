<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
Broadcast::channel('Match-updated.{liveId}', function ($user, $liveId) {
    // You can add additional authorization logic here if needed
    return true; // Allow all users to listen to live data updates
});
Broadcast::channel('Match-ended.{liveId}', function ($user, $liveId) {
    // You can add additional authorization logic here if needed
    return true; // Allow all users to listen to match end events
});
