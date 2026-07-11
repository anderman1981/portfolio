<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scan job portals twice daily: 6 AM and 6 PM
Schedule::command('jobs:scan')
    ->twiceDailyAt(6, 18, 0)
    ->withoutOverlapping()
    ->runInBackground();
