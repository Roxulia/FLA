<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Console\Commands\fetchLive;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:fetch-live')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onFailure(function () {
        // Handle failure, e.g., log an error or send a notification
        Log::error('Live data fetch command failed.');
    })
    ->onSuccess(function () {
        // Handle success, e.g., log a success message
        Log::info('Live data fetch command executed successfully.');
    });
