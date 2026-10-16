<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::command('quickbite:cleanup-stale-carts')
    ->daily();

Schedule::command('quickbite:cancel-unaccepted-orders')
    ->everyFiveMinutes();

Schedule::command('quickbite:send-daily-summary')
    ->dailyAt('23:00');