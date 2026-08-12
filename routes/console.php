<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Requires the server's cron to call `php artisan schedule:run` every
// minute — see README "SMS & WhatsApp Reminders" for the crontab entry.
Schedule::command('appointments:send-reminders')->dailyAt('09:00');
